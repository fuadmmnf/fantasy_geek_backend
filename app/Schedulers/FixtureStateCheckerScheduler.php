<?php


namespace App\Schedulers;

use App\Data\FixtureDetailDTO;
use App\Data\ScorecardStatsDTO;
use App\Handlers\CricApiDataProvider;
use App\Models\Fixture;
use App\Models\Scorecard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FixtureStateCheckerScheduler
{
    private CricApiDataProvider $cricApiProvider;

    public function __invoke()
    {
        $this->cricApiProvider = new CricApiDataProvider();
        $this->checkFixtureState();
    }

    private function checkFixtureState()
    {
        Log::debug('FixtureStateCheckerScheduler running');

        $now = Carbon::now();
        $fixtures = Fixture::whereIn('status', [0, 1])
            ->get()->filter(function ($fixture) use ($now) {
                return $fixture->status == 1 ||
                    ($fixture->status == 0 &&
                        $now->diffInMinutes($fixture->starting_time)) < 2;
            });

        $query_params = [
            'fields[object]' => 'toss_won_team_id,man_of_match_id,status',
        ];
        foreach ($fixtures as $fixture) {
            $fixtureDTO = $this->cricApiProvider->fetchFixtureInfo($fixture->api_fixtureid, $query_params);

            if ($fixtureDTO->status == 'Aban.') { // handle match abandoned
                $fixture->status = 3;
                $fixture->save();
                //refund coin to players

            } else if ($fixture->status == 0 && $fixtureDTO->toss_won_team_id != null) {
                $fixture->status = 1;
                //init scorecards
                $teammembers = array_merge($fixture->team1->team_members, $fixture->team2->team_members);
                foreach ($teammembers as $teammember) {
                    $newScorecard = new Scorecard();
                    $newScorecard->match_id = $fixture->id;
                    $newScorecard->player_id = $teammember->id;
                    $newScorecard->player_stats = (new ScorecardStatsDTO())->toArray();
                    $newScorecard->stat_points = (new ScorecardStatsDTO())->toArray();
                    $newScorecard->save();
                }

                $fixture->save();

            } else if ($fixture->status == 1 && $fixtureDTO->man_of_match_id != null) {
                $fixture->status = 2;
                $fixture->save();
                //distribute prize to winners
            }


        }
    }

}

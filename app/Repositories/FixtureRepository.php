<?php

namespace App\Repositories;

use App\Data\PlayerDTO;
use App\Data\TeamDetailDTO;
use App\Handlers\CricApiDataProvider;
use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Models\Contest;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\Pointdistribution;
use App\Models\Team;
use App\Models\Usercontest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\DataCollection;

class FixtureRepository
{

    public function getAllFixture($status = null)
    {
        $fixturees = Fixture::with('team1', 'team2')->withCount('contests');
        if ($status != null) {
            $fixturees = $fixturees->where('status', $status);
        }
        $fixturees = $fixturees->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $fixturees;
    }

    public function getFixture($fixture_id)
    {
        $fixture = Fixture::findOrFail($fixture_id);
        $fixture->load('team1', 'team2');

        return $fixture;
    }

    public function getUpcomingFixturees()
    {
        $fixturees = Fixture::where('status', 0)
            ->with('team1', 'team2')
            ->withCount('contests')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $fixturees;
    }

    public function getUpcomingFixtureesByUser($user_id)
    {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userUpcomingFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 0)
            ->with('team1', 'team2')
            ->get();

        return $userUpcomingFixturees;
    }

    public function getRunningFixtureesByUser($user_id)
    {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userRunningFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 1)
            ->with('team1', 'team2')
            ->get();

        return $userRunningFixturees;
    }

    public function getCompleteFixtureesByUser($user_id)
    {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userCompleteFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 2)
            ->with('team1', 'team2')
            ->get();

        return $userCompleteFixturees;
    }

    private function createTeam(int $api_teamid, int $api_seasonid): Team
    {
        //create player entities if not available
        $teamDetailDTO = (new CricApiDataProvider())->fetchSquadBySeason($api_teamid, $api_seasonid);
        $teammember_ids = $teamDetailDTO->squad->toCollection()->map(function (PlayerDTO $playerDTO) {
            $player = Player::where('api_pid', $playerDTO->id)->first();
            if (!$player) {
                $player = (new PlayerRepository())->storePlayer([
                    'playerposition_id' => $playerDTO->position->id, // id's are synced with our backend, via seeder
                    'name' => $playerDTO->fullname,
                    'battingstyle' => $playerDTO->battingstyle,
                    'bowlingstyle' => $playerDTO->bowlingstyle,
                    'api_pid' => $playerDTO->id,
                    'image' => $playerDTO->image_path
                ]);
            }
            return $player->id;
        })->all();

        return (new TeamRepository())->storeTeam([
            'name' => $teamDetailDTO->name,
            'type' => 0,
            'image' => $teamDetailDTO->image_path, // same image to local later
            'team_members' => $teammember_ids,
            'key_members' => [],
        ]);
    }

    public function storeFixture(array $request): ?Fixture
    {
        $searchFixture = Fixture::where('api_fixtureid', $request['api_fixtureid'])->first();
        if ($searchFixture) {
            return null;
        }

        $fixtureDetailDTO = (new CricApiDataProvider())->fetchFixtureInfo($request['api_fixtureid'], [
            'include' => 'localteam,visitorteam',
        ]);


        $newFixture = new Fixture();
        DB::beginTransaction();
        try {
            $newFixture->name = "{$fixtureDetailDTO->localteam->name}_{$fixtureDetailDTO->visitorteam->name}";
            $newFixture->pointdistribution_id = Pointdistribution::findOrFail($request['pointdistribution_id'])->id;
            $newFixture->team1_id = $this->createTeam($fixtureDetailDTO->localteam_id, $fixtureDetailDTO->season_id)->id;
            $newFixture->team2_id = $this->createTeam($fixtureDetailDTO->visitorteam_id, $fixtureDetailDTO->season_id)->id;
            $newFixture->starting_time = Carbon::parse($fixtureDetailDTO->starting_at);
            $newFixture->api_fixtureid = $fixtureDetailDTO->id;
            $newFixture->save();


        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
//
//        $cricketScorecardHandler = new CricketScorecardUpdater();
//        $cricketScorecardHandler->initPlayerScorecardForFixture($newFixture);

        return $newFixture;
    }


    public function updateFixture(array $request)
    {
        $fixture = Fixture::findOrFail($request['id']);
        $fixture->load('team1', 'team2');
        if (isset($request['starting_time'])) {
            $fixture->starting_time = $request['starting_time'];
        }
        if (isset($request['status'])) {
            $fixture->status = $request['status'];

            if ($request['status'] == 0) {//publishing a fixture, checking if all players are rated
                $team1_members = [];
                $team1 = $fixture->team1;
                foreach ($team1->team_members as $team_member) {
                    $player = Player::findOrFail($team_member['id']);
                    if ($player->rating == 0) {
                        return null;
                    }
                    $team_member['rating'] = $player->rating;
                    $team1_members[] = $team_member;
                }
                $team1->team_members = $team1_members;
                $team1->save();


                $team2_members = [];
                $team2 = $fixture->team2;

                foreach ($team2->team_members as $team2_member) {
                    $player2 = Player::findOrFail($team2_member['id']);
                    if ($player2->rating == 0) {
//                        Log::info(json_encode($team2_member) . '  rating: ' . $player2->rating);
                        return null;
                    }
                    $team2_member['rating'] = $player2->rating;
                    $team2_members[] = $team2_member;
                }
                $team2->team_members = $team2_members;
                $team2->save();

            }
        }

        $fixture->save();
        return $fixture;
    }
}

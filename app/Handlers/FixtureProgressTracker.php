<?php

namespace App\Handlers;

use App\Data\BattingScoreboardDTO;
use App\Data\BowlingScoreboardDTO;
use App\Data\FixtureDetailDTO;
use App\Data\ScorecardStatsDTO;
use App\Models\Fixture;
use App\Models\Scorecard;
use App\Models\Usercontest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class FixtureProgressTracker
{
    private Fixture $fixture;

    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }


    public function handleContestProgress(FixtureDetailDTO $fixtureDTO)
    {
        $scorecards = $this->fixture->scorecards;
        foreach (range(0, count($scorecards) - 1) as $i) {
            $this->calculateAndStorePoints($scorecards->slice($i, 1), $fixtureDTO);
        }

        foreach ($this->fixture->contests as $contest) {
            $usercontests = $contest->usercontests;
            foreach (range(0, count($usercontests) - 1) as $i) {

                $this->updateUserContestProgress($usercontests->slice($i, 1), $scorecards);
            }

            $contestStandings = array();
            $usercontests = $usercontests->sortByDesc('score');
            $j = 0;
            foreach ($usercontests as $usercontest) {
                $usercontest->ranking = ++$j;

                $usercontest->save();

                $contestStandings[] = [
                    'user_id' => $usercontest->user_id,
                    'user_name' => $usercontest->user->name,
                    'ranking' => $usercontest->ranking,
                    'score' => $usercontest->score
                ];
            }

            $contest->user_standings = $contestStandings;
            $contest->save();
        }

    }

    private function calculateAndStorePoints(Scorecard &$scorecard, FixtureDetailDTO $fixtureDetailDTO)
    {

        $batting = $fixtureDetailDTO->batting->toCollection()->where('player_id', $scorecard->player->api_pid)->first();
        $bowling = $fixtureDetailDTO->bowling->toCollection()->where('player_id', $scorecard->player->api_pid)->first();

//        Log::debug('batting: ' . ($batting == null? '[]': json_encode($batting->toArray(), true)));
//        Log::debug('bowling: ' . ($bowling == null? '[]': json_encode($bowling->toArray(), true)));
        $battingScoreboardDTO = ($batting == null) ? new BattingScoreboardDTO(player_id: $scorecard->player->api_pid) : BattingScoreboardDTO::from($batting->toArray()); // ->toArray()
        $bowlingScoreboardDTO = ($bowling == null) ? new BowlingScoreboardDTO(player_id: $scorecard->player->api_pid) : BowlingScoreboardDTO::from($bowling->toArray());
        //get point distributions
        $pd = json_decode($this->fixture->pointdistribution->distribution, true);
        $pd['econ_rate'] = ($bowling == null ? 0 : $this->getRatesFromRange(json_decode($pd['econ_rate'], true), $bowlingScoreboardDTO->rate) / ($bowlingScoreboardDTO->rate == 0 ? 1 : $bowlingScoreboardDTO->rate));
        $pd['strike_rate'] = ($batting == null ? 0 : $this->getRatesFromRange(json_decode($pd['strike_rate'], true), $battingScoreboardDTO->rate) / ($battingScoreboardDTO->rate == 0 ? 1 : $battingScoreboardDTO->rate));

        $pointDistributionDTO = ScorecardStatsDTO::from($pd);


        //get player stats
        $playerStats = new ScorecardStatsDTO();
        $playerStats->runs = $battingScoreboardDTO->score;
        $playerStats->runs_50 = floor($battingScoreboardDTO->score / 50);
        $playerStats->runs_100 = floor($battingScoreboardDTO->score / 100);
        $playerStats->four_x = $battingScoreboardDTO->four_x;
        $playerStats->six_x = $battingScoreboardDTO->six_x;
        $playerStats->duck = ($battingScoreboardDTO->score == 0 && ($battingScoreboardDTO->catch_stump_player_id != -1 || $battingScoreboardDTO->bowling_player_id != -1)) ? 1 : 0;
        $playerStats->strike_rate = $battingScoreboardDTO->rate;

        $playerStats->econ_rate = $bowlingScoreboardDTO->rate;
        $playerStats->wickets_1 = $bowlingScoreboardDTO->wickets;
        $playerStats->wickets_3 = floor($bowlingScoreboardDTO->wickets / 3);
        $playerStats->wickets_4 = floor($bowlingScoreboardDTO->wickets / 4);
        $playerStats->wickets_5 = floor($bowlingScoreboardDTO->wickets / 5);
        $playerStats->maiden_overs = $bowlingScoreboardDTO->medians;
        $playerStats->run_outs = $fixtureDetailDTO->batting->toCollection()->filter(function (BattingScoreboardDTO $val) use ($scorecard) {
            return $val->runout_by_id == $scorecard->player->api_pid;
        })->count();
        $playerStats->catches_stumpings = $fixtureDetailDTO->batting->toCollection()->filter(function (BattingScoreboardDTO $val) use ($scorecard) {
            return $val->catch_stump_player_id == $scorecard->player->api_pid;
        })->count();

        //calculate points per attribute
        $playerPointScores = array_merge_recursive($pointDistributionDTO->toArray(), $playerStats->toArray());
        array_walk($playerPointScores, function (&$v, $k) {
            $v = $v[0] * $v[1];
        });


        $scorecard->player_stats = $playerStats->toArray();
        $scorecard->stat_points = $playerPointScores;
        $scorecard->score = array_sum($playerPointScores);
        $scorecard->save();

    }

    private function getRatesFromRange($ranges, $val): float
    {
        foreach (array_keys($ranges) as $key) {
            if ($val <= floatval($key)) {
                return $ranges[$key];
            }
        }
        return 0.0;
    }

    private function updateUserContestProgress(Usercontest &$usercontest, Collection $scorecards)
    {

        $playerIds = array_map(fn($player): int => $player['id'], $usercontest->team->team_members);
        $key_members = $usercontest->team->key_members;
        $playerScorecards = $scorecards->filter(function ($scorecard) use ($playerIds) {
            return in_array($scorecard->player_id, $playerIds);
        });
        $usercontest->team_stats = $playerScorecards->map(function ($item) use ($key_members) {
            $factor = ($item->player_id == $key_members[0] ? 2.0 : ($item->player_id == $key_members[1] ? 1.5 : 1)); // first index in captain id, second index in vicecaptain
            return [
                'id' => $item->player_id,
                'name' => $item->player->name,
                'playerposition_id' => $item->player->playerposition_id,
                'image' => $item->player->image,
                'score' => $item->score * $factor,
            ];
        })->values()->toArray();
        $usercontest->score = $playerScorecards->reduce(function ($carry, $item) use ($key_members) {
            $factor = ($item->player_id == $key_members[0] ? 2.0 : ($item->player_id == $key_members[1] ? 1.5 : 1)); // first index in captain id, second index in vicecaptain
            return $carry + $item->score * $factor;
        }, 0);

    }

}

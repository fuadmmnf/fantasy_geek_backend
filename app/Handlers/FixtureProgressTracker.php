<?php

namespace App\Handlers;

use App\Data\BattingScoreboardDTO;
use App\Data\BowlingScoreboardDTO;
use App\Data\FixtureDetailDTO;
use App\Data\ScorecardStatsDTO;
use App\Models\Fixture;
use App\Models\Scorecard;
use App\Models\Usercontest;
use Ramsey\Collection\Collection;

class FixtureProgressTracker {
    private Fixture $fixture;

    public function __construct(Fixture $fixture) {
        $this->fixture = $fixture;
    }


    public function handleContestProgress(FixtureDetailDTO $fixtureDTO) {
        $scorecards = $this->fixture->scorecards;
        foreach (range(0, count($scorecards)) as $i) {
            $this->calculateAndStorePoints($scorecards[$i], $fixtureDTO);
        }

        foreach ($this->fixture->contests as $contest){
            $usercontests = $contest->usercontests;
            foreach (range(0, count($usercontests)) as $i){
                $this->updateUserContestProgress($usercontests[$i], $scorecards);
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

    private function calculateAndStorePoints(Scorecard &$scorecard, FixtureDetailDTO $fixtureDetailDTO) {
        $battingScoreboardDTO = BattingScoreboardDTO::from($fixtureDetailDTO->batting->where('player_id', $scorecard->player->api_pid)->first()); // ->toArray()
        $bowlingScoreboardDTO = BowlingScoreboardDTO::from($fixtureDetailDTO->bowling->where('player_id', $scorecard->player->api_pid)->first());

        //get point distributions
        $pd = json_decode($this->fixture->pointdistribution->distribution);
        $pd['econ_rate'] = $this->getRatesFromRange(json_decode($pd['econ_rate']), $bowlingScoreboardDTO->rate) / $bowlingScoreboardDTO->rate;
        $pd['strike_rate'] = $this->getRatesFromRange(json_decode($pd['strike_rate']), $battingScoreboardDTO->rate) / $battingScoreboardDTO->rate;
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
        $playerStats->run_outs = $fixtureDetailDTO->batting->filter(function ($val) use ($scorecard) {
            return $val->runout_by_id == $scorecard->player->api_pid;
        })->count();
        $playerStats->catches_stumpings = $fixtureDetailDTO->batting->filter(function ($val) use ($scorecard) {
            return $val->catch_stump_player_id == $scorecard->player->api_pid;
        })->count();

        //calculate points per attribute
        $playerPointScores = array_merge_recursive($pointDistributionDTO->toArray(), $playerStats->toArray());
        array_walk($playerPointScores, function(&$v, $k) { $v = $v[0]*$v[1]; });

        $scorecard->player_stats = $playerStats->toArray();
        $scorecard->stat_points = $playerPointScores;
        $scorecard->score = array_sum($playerPointScores);
        $scorecard->save();
    }

    private function getRatesFromRange($ranges, $val): float {
        foreach (array_keys($ranges) as $key) {
            if ($val <= floatval($key)) {
                return $ranges[$key];
            }
        }
        return 0.0;
    }

    private function updateUserContestProgress(Usercontest &$usercontest, Collection $scorecards){
        $playerIds = $usercontest->team->team_members;
        $key_members = $usercontest->team->key_members;
        $playerScorecards = $scorecards->filter(function ($scorecard) use ($playerIds) {
            return in_array($scorecard->player_id, $playerIds);
        });
        $usercontest->team_stats = $playerScorecards->transform(function ($item) {
            return $item->player_stats;
        })->all();
        $usercontest->score = $playerScorecards->reduce(function ($carry, $item) use ($key_members) {
            $factor = $item->player_id == $key_members['captain_id'] ? 2.0 : ($item->player_id == $key_members['vicecaptain_id'] ? 1.5 : 1);
            return $carry + $item->score * $factor;
        });

    }

}

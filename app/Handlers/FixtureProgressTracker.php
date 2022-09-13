<?php

use App\Data\BattingScoreboardDTO;
use App\Data\BowlingScoreboardDTO;
use App\Data\FixtureDetailDTO;
use App\Data\PointDistributionDTO;
use App\Models\Fixture;
use App\Models\Scorecard;

class FixtureProgressTracker {
    private $fixture;

    public function __construct(Fixture $fixture) {
        $this->fixture = $fixture;
    }


    public function handleContestProgress(FixtureDetailDTO $fixtureDTO) {
        $scorecards = $this->fixture->scorecards;
        foreach ($scorecards as $scorecard) {
            $batting_score = $fixtureDTO->batting->where('player_id', $scorecard->player->api_pid)->first();
            $bowling_score = $fixtureDTO->bowling->where('player_id', $scorecard->player->api_pid)->first();

            // calculate run outs, catches, etc here
            $this->calculatePoints($scorecard, $batting_score, $bowling_score);
        }
    }

    private function calculatePoints(Scorecard $scorecard, BattingScoreboardDTO $battingScoreboardDTO, BowlingScoreboardDTO $bowlingScoreboardDTO) {
        $pd = json_decode($this->fixture->pointdistribution->distribution);
        $pd['econ_rate'] = json_decode($pd['econ_rate']);
        $pd['strike_rate'] = json_decode($pd['strike_rate']);
        $pointDistributionDTO = PointDistributionDTO::from($pd);
        $playerStats = new PointDistributionDTO();
        $playerPointScores = new PointDistributionDTO();

        $playerStats->runs = $battingScoreboardDTO->score;
        $playerStats->runs_50 = floor($battingScoreboardDTO->score / 50);
        $playerStats->runs_100 = floor($battingScoreboardDTO->score / 100);
        $playerStats->four_x = $battingScoreboardDTO->four_x;
        $playerStats->six_x = $battingScoreboardDTO->six_x;
        $playerStats->duck = ($battingScoreboardDTO->score == 0 && ($battingScoreboardDTO->catch_stump_player_id != -1 || $battingScoreboardDTO->bowling_player_id != -1)) ? 1 : 0;
        $playerStats->strike_rate = $battingScoreboardDTO->rate;


        $playerStats->econ_rate = $bowlingScoreboardDTO->rate;
        $playerStats->wickets_1 = $bowlingScoreboardDTO->wickets;
        $playerStats->wickets_3 = floor($bowlingScoreboardDTO->wickets/3);
        $playerStats->wickets_4 = floor($bowlingScoreboardDTO->wickets/4);
        $playerStats->wickets_5 = floor($bowlingScoreboardDTO->wickets/5);
        $playerStats->maiden_overs = $bowlingScoreboardDTO->medians;
        $playerStats->run_outs = $bowlingScoreboardDTO->rate; // needs processing
        $playerStats->catches_stumpings = $bowlingScoreboardDTO->rate; // needs processing
        //get player infos and $bowlingScoreboardDTO point
        //update scorecard

    }

    private function updateUserContestScores($scorecards) {
        $contests = $this->fixture->contests;
        $scorecardsArr = array();
        foreach ($scorecards as $scorecard) {
            $scorecardsArr[$scorecard->player_id] = $scorecard;
        }


        foreach ($contests as $contest) {
            $usercontests = $contest->usercontests;
            for ($i = 0; $i < count($usercontests); $i++) {
                $this->storeContestUserScores($usercontests[$i], $scorecardsArr);
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

            $contest->user_standings = json_encode($contestStandings);
            $contest->save();
        }
    }

    private function storeContestUserScores(Usercontest &$usercontest, &$scorecardsArr) {
        $teammembers = json_decode($usercontest->team->team_members, true);
        $teamstatsArr = array();
        $contestantScore = 0.0;

        foreach ($teammembers as $teammember) {
            $player_id = $teammember['id'];
            $teamstatsArr[$player_id] = [
                "score" => $scorecardsArr[$player_id]->score,
                "stats" => json_decode($scorecardsArr[$player_id]->player_stats)
            ];
            $playerScore = $scorecardsArr[$player_id]->score;
            if ($usercontest->captain_id == $player_id) {
                $playerScore *= 2;
            } elseif ($usercontest->vicecaptain_id == $player_id) {
                $playerScore *= 1.5;
            }
            $contestantScore += $playerScore;
        }
        $usercontest->team_stats = json_encode($teamstatsArr);
        $usercontest->score = $contestantScore;
    }


    public function initPlayerScorecardForFixture(Fixture $fixture) {
        $initPlayerStat = array(
            'is_in_starting_xi' => false,
            'is_dismissed' => false,
            'balls_played' => 0,
            'runs' => 0,
            '4s' => 0,
            '6s' => 0,
            'strike_rate' => 0,
            'overs_bowled' => 0,
            'wickets' => 0,
            'maiden_overs' => 0,
            'econ_rate' => 0,
            'run_outs' => 0,
            'stumpings' => 0,
            'catches' => 0,
        );

        $initStatPoints = array(
            'is_in_starting_xi' => 0,
            'is_dismissed' => 0,
            'balls_played' => 0,
            'runs' => 0,
            '4s' => 0,
            '6s' => 0,
            'strike_rate' => 0,
            'overs_bowled' => 0,
            'wickets' => 0,
            'maiden_overs' => 0,
            'econ_rate' => 0,
            'run_outs' => 0,
            'stumpings' => 0,
            'catches' => 0,
        );
        if (!Scorecard::where('fixture_id', $fixture->id)->first()) {
            $teammembers = array_merge(json_decode($fixture->team1->team_members), json_decode($fixture->team2->team_members));
            foreach ($teammembers as $teammember) {
                $newScorecard = new Scorecard();
                $newScorecard->fixture_id = $fixture->id;
                $newScorecard->player_id = $teammember->id;
                $newScorecard->player_stats = json_encode($initPlayerStat);
                $newScorecard->stat_points = json_encode($initStatPoints);
                $newScorecard->save();
            }
        }

    }


    public function parseAndStoreFixtureScorecard(Fixture $fixture, $fixtureSummary) {
//        $this->initPlayerScorecardForFixture($fixture);
//        dd(array_merge($fixtureSummary['team'][0]['players'], $fixtureSummary['team'][1]['players']));
        $scorecards = $fixture->scorecards;
        $pointdistributionDTO = PointDistributionDTO::from(json_decode($fixture->pointdistribution->distribution, true));

        if ($fixture->status == 1 && $fixtureSummary['fixtureStarted']) {
            $teamsquads = array_merge($fixtureSummary['team'][0]['players'], $fixtureSummary['team'][1]['players']);
            $this->updateStartingLineupStat($scorecards, $teamsquads);
        }

        $this->updatePerformanceStat($scorecards, $fixtureSummary, $pointdistributionDTO);
        return $scorecards;
    }

    private function updateStartingLineupStat(&$scorecards, $teamsquads) {
        foreach ($scorecards as $scorecard) {
            $player = $scorecard->player;
            foreach ($teamsquads as $key => $val) {
                if ($val['pid'] === $player->api_pid) {
                    $stats = json_decode($scorecard->player_stats);
                    $stats->is_in_starting_xi = true;
                    $scorecard->player_stats = json_encode($stats);
                    $scorecard->save();
                }
            }
        }

    }


    private function updatePerformanceStat(&$scorecards, $fixtureSummary, $pointdistribution) {
        $statsArr = array();


        $battingStats = (count($fixtureSummary['batting']) > 1) ? array_merge($fixtureSummary['batting'][0]['scores'], $fixtureSummary['batting'][1]['scores']) : $fixtureSummary['batting'][0]['scores'];
        $bowlingStats = (count($fixtureSummary['bowling']) > 1) ? array_merge($fixtureSummary['bowling'][0]['scores'], $fixtureSummary['bowling'][1]['scores']) : $fixtureSummary['bowling'][0]['scores'];
        $fieldingStats = (count($fixtureSummary['fielding']) > 1) ? array_merge($fixtureSummary['fielding'][0]['scores'], $fixtureSummary['fielding'][1]['scores']) : $fixtureSummary['fielding'][0]['scores'];

        foreach ($battingStats as $battingStat) {
            $statsArr[$battingStat['pid'] . "_bat"] = $battingStat;
        }
        foreach ($bowlingStats as $bowlingStat) {
            $statsArr[$bowlingStat['pid'] . "_bwl"] = $bowlingStat;
        }
        foreach ($fieldingStats as $fieldingStat) {
            $statsArr[$fieldingStat['pid'] . "_fld"] = $fieldingStat;
        }

        foreach ($scorecards as $scorecard) {
            $player = $scorecard->player;
            $attr = json_decode($scorecard->player_stats, true);


            $attr['is_dismissed'] = isset($statsArr[$player->api_pid . '_bat']['dismissal-by']) && isset($statsArr[$player->api_pid . '_bat']['dismissal-by']['pid']);
            $attr['balls_played'] = (isset($statsArr[$player->api_pid . '_bat']) && isset($statsArr[$player->api_pid . '_bat']['B'])) ? $statsArr[$player->api_pid . '_bat']['B'] : $attr['balls_played'];
            $attr['runs'] = (isset($statsArr[$player->api_pid . "_bat"]) && isset($statsArr[$player->api_pid . '_bat']['R'])) ? $statsArr[$player->api_pid . "_bat"]['R'] : $attr['runs'];
            $attr['4s'] = (isset($statsArr[$player->api_pid . "_bat"]) && isset($statsArr[$player->api_pid . '_bat']['4s'])) ? $statsArr[$player->api_pid . "_bat"]['4s'] : $attr['4s'];
            $attr['6s'] = (isset($statsArr[$player->api_pid . "_bat"]) && isset($statsArr[$player->api_pid . '_bat']['6s'])) ? $statsArr[$player->api_pid . "_bat"]['6s'] : $attr['6s'];
            $attr['strike_rate'] = (isset($statsArr[$player->api_pid . "_bat"]) && isset($statsArr[$player->api_pid . '_bat']['SR'])) ? $statsArr[$player->api_pid . "_bat"]['SR'] : $attr['strike_rate'];
            $attr['overs_bowled'] = floatval((isset($statsArr[$player->api_pid . "_bwl"]) && isset($statsArr[$player->api_pid . '_bwl']['O'])) ? $statsArr[$player->api_pid . "_bwl"]['O'] : $attr['overs_bowled']);
            $attr['wickets'] = floatval((isset($statsArr[$player->api_pid . "_bwl"]) && isset($statsArr[$player->api_pid . '_bwl']['W'])) ? $statsArr[$player->api_pid . "_bwl"]['W'] : $attr['wickets']);
            $attr['maiden_overs'] = floatval((isset($statsArr[$player->api_pid . "_bwl"]) && isset($statsArr[$player->api_pid . '_bwl']['M'])) ? $statsArr[$player->api_pid . "_bwl"]['M'] : $attr['maiden_overs']);
            $attr['econ_rate'] = floatval((isset($statsArr[$player->api_pid . "_bwl"]) && isset($statsArr[$player->api_pid . '_bwl']['Econ'])) ? $statsArr[$player->api_pid . "_bwl"]['Econ'] : $attr['econ_rate']);
            $attr['run_outs'] = (isset($statsArr[$player->api_pid . "_fld"]) && isset($statsArr[$player->api_pid . '_fld']['runout'])) ? $statsArr[$player->api_pid . "_fld"]['runout'] : $attr['run_outs'];
            $attr['stumpings'] = (isset($statsArr[$player->api_pid . "_fld"]) && isset($statsArr[$player->api_pid . '_fld']['stumped'])) ? $statsArr[$player->api_pid . "_fld"]['stumped'] : $attr['stumpings'];
            $attr['catches'] = (isset($statsArr[$player->api_pid . "_fld"]) && isset($statsArr[$player->api_pid . '_fld']['catch'])) ? $statsArr[$player->api_pid . "_fld"]['catch'] : $attr['catches'];

            $this->setPlayerScores($scorecard, $attr, $pointdistribution);
            $scorecard->player_stats = json_encode($attr);

            $scorecard->save();
        }
    }

    private function setPlayerScores(Scorecard &$scorecard, &$attr, $pointdistribution) {
        if (!$attr['is_in_starting_xi'])
            return;
        $statPoints = json_decode($scorecard->stat_points, true);
        $score = 0.0;
        $val = $attr['is_in_starting_xi'] * $pointdistribution['is_in_starting_xi'];
        $statPoints['is_in_starting_xi'] = $val;
        $score += $val;

        $val = $attr['runs'] * $pointdistribution['runs'];
        $statPoints['runs'] = $val;
        $score += $val;

        $val = $attr['4s'] * $pointdistribution['4s'];
        $statPoints['4s'] = $val;
        $score += $val;

        $val = $attr['6s'] * $pointdistribution['6s'];
        $statPoints['6s'] = $val;
        $score += $val;

        $val = $attr['wickets'] * $pointdistribution['wickets'];
        $statPoints['wickets'] = $val;
        $score += $val;

        $val = $attr['maiden_overs'] * $pointdistribution['maiden_overs'];
        $statPoints['maiden_overs'] = $val;
        $score += $val;

        $val = $attr['run_outs'] * $pointdistribution['run_outs'];
        $statPoints['run_outs'] = $val;
        $score += $val;

        $val = $attr['stumpings'] * $pointdistribution['stumpings'];
        $statPoints['stumpings'] = $val;
        $score += $val;

        $val = $attr['catches'] * $pointdistribution['catches'];
        $statPoints['catches'] = $val;
        $score += $val;

        if ($scorecard->player_id == 21) {
            error_log($score);
        }
        //special batting stats


        $runsLeft = $attr['runs'];
        $val = ($attr['is_dismissed'] && $runsLeft == 0) * $pointdistribution['duck'];
        $statPoints['runs'] += $val;
        $score += $val;


        if ($runsLeft >= 100) {
            $centuries = intdiv($runsLeft, 100);
            $val = $centuries * $pointdistribution['century'];
            $statPoints['runs'] += $val;
            $score += $val;
            $runsLeft -= $centuries * 100;
        }
        if ($runsLeft >= 50) {
            $val = $pointdistribution['half_century'];
            $statPoints['runs'] += $val;
            $score += $val;
        }

        if ($scorecard->player->playerposition->name != 'bwl' && $attr['balls_played'] > 0 && $attr['strike_rate'] != 0) {
            foreach (json_decode($pointdistribution['strike_rate'], true) as $key => $v) {
                $limits = explode('-', $key);
                if ($attr['strike_rate'] >= floatval($limits[0]) && $attr['strike_rate'] <= floatval($limits[1])) {
                    $statPoints['strike_rate'] = $v;
                    $score += $v;
                    break;
                }
            }
        }


        //special bowling stats
        $bowlerWickets = $attr['wickets'];
        if ($bowlerWickets >= 5) {
            $pentahauls = intdiv($bowlerWickets, 5);
            $val = $pentahauls * $pointdistribution['5wicket'];
            $statPoints['wickets'] += $val;
            $score += $val;
            $bowlerWickets -= $pentahauls * 5;
        }
        if ($bowlerWickets >= 4) {
            $tetrahauls = intdiv($bowlerWickets, 4);
            $val = $tetrahauls * $pointdistribution['4wicket'];
            $statPoints['wickets'] += $val;
            $score += $val;
            $bowlerWickets -= $tetrahauls * 4;
        }

        if ($bowlerWickets >= 3) {
            $trihauls = intdiv($bowlerWickets, 3);
            $val = $trihauls * $pointdistribution['3wicket'];
            $statPoints['wickets'] += $val;
            $score += $val;
            $bowlerWickets -= $trihauls * 3;
        }

        if ($attr['overs_bowled'] >= 2) {
            foreach (json_decode($pointdistribution['econ_rate'], true) as $key => $v) {
                $limits = explode('-', $key);
                if ($attr['econ_rate'] >= floatval($limits[0]) && $attr['econ_rate'] <= floatval($limits[1])) {
                    $statPoints['econ_rate'] = $v;
                    $score += $v;
                    break;
                }
            }
        }

        if ($scorecard->player_id == 21) {
            error_log($score);
        }


        $scorecard->stat_points = json_encode($statPoints);
        $scorecard->score = $score;
        $scorecard->save();
    }


    public function parseAndUpdateFixtureState(Fixture $fixture, $fixtureSummary) {
        if ($fixture->status == 1 && array_key_exists('man-of-the-fixture', $fixtureSummary) && isset($fixtureSummary['man-of-the-fixture']['pid'])) {
            $fixture->status = 2;
            $fixture->save();
        } elseif ($fixture->status == 0 && isset($fixtureSummary['fixtureStarted']) && strlen($fixtureSummary['toss_winner_team']) > 0) {
            $fixture->status = 1;
            $fixture->save();
        }
        return $fixture->status;
    }
}

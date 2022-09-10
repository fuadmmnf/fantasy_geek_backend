<?php


namespace App\Handlers\Scorecard;

use App\Models\Match;
use App\Models\Scorecard;

class CricketScorecardUpdater implements ScorecardStrategy
{

    public function initPlayerScorecardForMatch(Match $match)
    {
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
        if (!Scorecard::where('match_id', $match->id)->first()) {
            $teammembers = array_merge(json_decode($match->team1->team_members), json_decode($match->team2->team_members));
            foreach ($teammembers as $teammember) {
                $newScorecard = new Scorecard();
                $newScorecard->match_id = $match->id;
                $newScorecard->player_id = $teammember->id;
                $newScorecard->player_stats = json_encode($initPlayerStat);
                $newScorecard->stat_points = json_encode($initStatPoints);
                $newScorecard->save();
            }
        }

    }


    public function parseAndStoreMatchScorecard(Match $match, $matchSummary)
    {
//        $this->initPlayerScorecardForMatch($match);
//        dd(array_merge($matchSummary['team'][0]['players'], $matchSummary['team'][1]['players']));
        $scorecards = $match->scorecards;
        $pointdistribution = json_decode($match->pointdistribution->distribution, true);

        if ($match->status == 1 && $matchSummary['matchStarted']) {
            $teamsquads = array_merge($matchSummary['team'][0]['players'], $matchSummary['team'][1]['players']);
            $this->updateStartingLineupStat($scorecards, $teamsquads);
        }

        $this->updatePerformanceStat($scorecards, $matchSummary, $pointdistribution);
        return $scorecards;
    }

    private function updateStartingLineupStat(&$scorecards, $teamsquads)
    {
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


    private function updatePerformanceStat(&$scorecards, $matchSummary, $pointdistribution)
    {
        $statsArr = array();


        $battingStats = (count($matchSummary['batting']) > 1) ? array_merge($matchSummary['batting'][0]['scores'], $matchSummary['batting'][1]['scores']) : $matchSummary['batting'][0]['scores'];
        $bowlingStats = (count($matchSummary['bowling']) > 1) ? array_merge($matchSummary['bowling'][0]['scores'], $matchSummary['bowling'][1]['scores']) : $matchSummary['bowling'][0]['scores'];
        $fieldingStats = (count($matchSummary['fielding']) > 1) ? array_merge($matchSummary['fielding'][0]['scores'], $matchSummary['fielding'][1]['scores']) : $matchSummary['fielding'][0]['scores'];

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

    private function setPlayerScores(Scorecard &$scorecard, &$attr, $pointdistribution)
    {
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


    public function parseAndUpdateMatchState(Match $match, $matchSummary)
    {
        if ($match->status == 1 && array_key_exists('man-of-the-match', $matchSummary) && isset($matchSummary['man-of-the-match']['pid'])) {
            $match->status = 2;
            $match->save();
        } elseif ($match->status == 0 && isset($matchSummary['matchStarted']) && strlen($matchSummary['toss_winner_team']) > 0) {
            $match->status = 1;
            $match->save();
        }
        return $match->status;
    }
}

<?php


namespace App\Handlers\Scorecard;


use App\Models\Match;

interface ScorecardStrategy
{
    public function initPlayerScorecardForMatch(Match $match);
    public function parseAndStoreMatchScorecard(Match $match, $matchSummary);
}

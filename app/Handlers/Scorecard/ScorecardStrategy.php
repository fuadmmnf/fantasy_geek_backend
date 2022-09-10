<?php


namespace App\Handlers\Scorecard;


use App\Models\Fixture;

interface ScorecardStrategy
{
    public function initPlayerScorecardForMatch(Fixture $match);
    public function parseAndStoreMatchScorecard(Fixture $match, $matchSummary);
}

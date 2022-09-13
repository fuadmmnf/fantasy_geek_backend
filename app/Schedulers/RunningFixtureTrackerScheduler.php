<?php


namespace App\Schedulers;


use App\Models\Fixture;
use FixtureProgressTracker;
use Illuminate\Support\Facades\Log;

class RunningFixtureTrackerScheduler
{
    private \CricApiDataProvider $cricApiProvider;
    public function __invoke()
    {
        $this->cricApiProvider = new \CricApiDataProvider();
        $this->checkRunningFixtureAndUpdateContestScores();
    }

    private function checkRunningFixtureAndUpdateContestScores()
    {
        Log::debug('RunningContestScheduler running');
        $runningFixtures = Fixture::where('status', 1)->get();
        $runningFixtures->load('pointdistribution', 'scorecards');
        foreach ($runningFixtures as $runningFixture){
            $scorecards = $this->cricApiProvider->fetchFixtureScoreboard($runningFixture->api_fixtureid);
            (new FixtureProgressTracker($runningFixture))->handleContestProgress($scorecards);
         }
    }

}

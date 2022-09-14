<?php


namespace App\Schedulers;


use App\Handlers\CricApiDataProvider;
use App\Handlers\FixtureProgressTracker;
use App\Models\Fixture;
use Illuminate\Support\Facades\Log;

class RunningFixtureTrackerScheduler
{
    private CricApiDataProvider $cricApiProvider;
    public function __invoke()
    {
        $this->cricApiProvider = new CricApiDataProvider();
        $this->checkRunningFixtureAndUpdateContestScores();
    }

    private function checkRunningFixtureAndUpdateContestScores()
    {
        Log::debug('RunningContestScheduler running');
        $runningFixtures = Fixture::where('status', 1)->get();
        $runningFixtures->load('pointdistribution', 'scorecards', 'scorecards.player', 'contests', 'contests.team', 'contests.usercontests');
        foreach ($runningFixtures as $runningFixture){
            $fixtureDTO = $this->cricApiProvider->fetchFixtureScoreboard($runningFixture->api_fixtureid);
            (new FixtureProgressTracker($runningFixture))->handleContestProgress($fixtureDTO);
         }
    }

}

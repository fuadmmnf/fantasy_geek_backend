<?php


namespace App\Schedulers;


use App\Handlers\ContestProgressHandler;
use App\Match;
use Illuminate\Support\Facades\Log;

class RunningContestScheduler
{
    public function __invoke()
    {
        $this->checkRunningMatchAndUpdateContestScores();
    }

    private function checkRunningMatchAndUpdateContestScores()
    {
        Log::debug('RunningContestScheduler running');
        $runningMatches = Match::where('status', 1)->get();
        foreach ($runningMatches as $runningMatch){
            $contestProgressHandler = new ContestProgressHandler($runningMatch);
            $contestProgressHandler->handleContestProgress();
        }
    }

}

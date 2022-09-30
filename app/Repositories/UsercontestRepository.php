<?php

namespace App\Repositories;

use App\Models\Contest;
use App\Models\Usercontest;
use Illuminate\Support\Facades\DB;

class UsercontestRepository
{
    public function getConstestsByFixture($user_id, $fixture_id){

        $fixtureContestIds = Contest::where('fixture_id', $fixture_id)->pluck('id');
        $userContestByFixture = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $fixtureContestIds)
            ->get();

        $userContestByFixture->load('contest');

        return $userContestByFixture;
    }

    public function getDetail($contest_id){
        $contest = Contest::findOrFail($contest_id);
        error_log($contest);
        return $contest;
    }


}

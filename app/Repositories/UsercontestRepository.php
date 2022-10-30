<?php

namespace App\Repositories;

use App\Models\Contest;
use App\Models\Fixture;
use App\Models\Team;
use App\Models\User;
use App\Models\Usercontest;
use App\Models\Userfixtureteam;
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

    public function getUsercontestsById($user_id, $contest_id){
        $user = User::findOrFail($user_id);

        $usercontest = Usercontest::where('user_id', $user->id)
            ->where('contest_id', $contest_id)
            ->first();

        return $usercontest;
    }
    public function getUsercontestsRankingById($contest_id){
        $ranking = Usercontest::where('contest_id', $contest_id)
            ->orderBy('ranking', 'ASC')
            ->paginate(10);

        return $ranking;
    }

    public function getUserUpcomingContests($user_id){

        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        $upcomingFixtureIds = Fixture::where('status', 0)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }
    public function getUserOngoingContests($user_id){

        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        $upcomingFixtureIds = Fixture::where('status', 1)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }

     public function getUserCompletedContests($user_id){

        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        $upcomingFixtureIds = Fixture::where('status', 2)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }

    public function createUsercontest(array $request){
        $user = User::findOrFail($request['user_id']);
        $team = Team::findOrFail($request['team_id']);
        $contest = Contest::findOrFail($request['contest_id']);

        $newUsercontest = new Usercontest();

        $newUsercontest->user_id = $user->id;
        $newUsercontest->team_id = $team->id;
        $newUsercontest->contest_id = $contest->id;
        $newUsercontest->transaction_id = $request['transaction_id']??0;

        $newUsercontest->save();

        return $newUsercontest;
    }


}

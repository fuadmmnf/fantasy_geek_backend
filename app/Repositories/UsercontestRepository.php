<?php

namespace App\Repositories;

use App\Models\Contest;
use App\Models\Fixture;
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

    public function getUserUpcomingContests($user_id){

        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        error_log('match--'. $matchIdsByUser);

        $upcomingFixtureIds = Fixture::where('status', 0)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        error_log('match--'. $matchIdsByUser);

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');


        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }
    public function getUserOngoingContests($user_id){

            $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

            error_log('match--'. $matchIdsByUser);

            $upcomingFixtureIds = Fixture::where('status', 1)
                ->whereIn('id', $matchIdsByUser)->pluck('id');

            error_log('match--'. $matchIdsByUser);

            $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

            $userUpcomingContests = Usercontest::where('user_id', $user_id)
                ->whereIn('contest_id', $contestIdsByFixture)->get();

            $userUpcomingContests->load('contest');

            return $userUpcomingContests;
        }

     public function getUserCompletedContests($user_id){

                $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

                error_log('match--'. $matchIdsByUser);

                $upcomingFixtureIds = Fixture::where('status', 2)
                    ->whereIn('id', $matchIdsByUser)->pluck('id');

                error_log('match--'. $matchIdsByUser);

                $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

                $userUpcomingContests = Usercontest::where('user_id', $user_id)
                    ->whereIn('contest_id', $contestIdsByFixture)->get();

                $userUpcomingContests->load('contest');

                return $userUpcomingContests;
            }


}

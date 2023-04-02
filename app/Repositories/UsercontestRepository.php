<?php

namespace App\Repositories;

use App\Models\Contest;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\Scorecard;
use App\Models\Team;
use App\Models\User;
use App\Models\Usercontest;
use App\Models\Userfixtureteam;
use Illuminate\Support\Facades\DB;

class UsercontestRepository
{
    public function getConstestsByFixture($user_id, $fixture_id)
    {


        $fixtureContestIds = Contest::where('fixture_id', $fixture_id)->pluck('id');
        $userContestByFixture = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $fixtureContestIds)
            ->get();

        $userContestByFixture->load('contest');

        return $userContestByFixture;
    }

    public function getScorecardByPlayer($usercontest_id, $player_id)
    {
        $usercontest = Usercontest::findOrFail($usercontest_id);
        $usercontest->load('team', 'contest');
        $player = Player::findOrFail($player_id);
        $scorecard = Scorecard::where('fixture_id', $usercontest->contest->fixture_id)
            ->where('player_id', $player_id)->firstOrFail();

        return [
            'name' => $player->name,
            'image' => $player->image,
            'rating' => $player->rating,
            'playerposition_id' => $player->playerposition_id,
            'bowlingstyle' => $player->bowlingstyle,
            'battingstyle' => $player->battingstyle,
            'is_captain' => $usercontest->team->key_members[0] == $player_id,
            'is_vicecaptain' => $usercontest->team->key_members[1] == $player_id,
            'player_stats' => $scorecard->player_stats,
            'stat_points' => $scorecard->stat_points,
            'score' => $scorecard->score * ($scorecard->player_id == $usercontest->team->key_members[0] ? 2.0 : ($scorecard->player_id == $usercontest->team->key_members[1] ? 1.5 : 1))
        ];
    }

    public function getUsercontestsById($user_id, $contest_id)
    {
//        $user = User::findOrFail($user_id);

        $usercontest = Usercontest::where('user_id', $user_id)
            ->where('contest_id', $contest_id)
            ->firstOrFail();
        $usercontest->load('user', 'team', 'contest');
        return $usercontest;
    }

    public function getUsercontestsRankingById($contest_id)
    {
        $ranking = Usercontest::where('contest_id', $contest_id)
            ->orderBy('ranking', 'ASC')
            ->paginate(10);
        $ranking->load('user');
        return $ranking;
    }

    public function getUserUpcomingContests($user_id)
    {


        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        $upcomingFixtureIds = Fixture::where('status', 0)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }

    public function getUserOngoingContests($user_id)
    {


        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        $upcomingFixtureIds = Fixture::where('status', 1)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }

    public function getUserCompletedContests($user_id)
    {


        $matchIdsByUser = Userfixtureteam::where('user_id', $user_id)->pluck('fixture_id');

        $upcomingFixtureIds = Fixture::where('status', 2)
            ->whereIn('id', $matchIdsByUser)->pluck('id');

        $contestIdsByFixture = Contest::whereIn('fixture_id', $upcomingFixtureIds)->pluck('id');

        $userUpcomingContests = Usercontest::where('user_id', $user_id)
            ->whereIn('contest_id', $contestIdsByFixture)->get();

        $userUpcomingContests->load('contest');

        return $userUpcomingContests;
    }

    public function createUsercontest(array $request)
    {
        $user = User::findOrFail($request['user_id']);
        $team = Team::findOrFail($request['team_id']);
        $contest = Contest::findOrFail($request['contest_id']);

        DB::beginTransaction();
        try {

            $newUsercontest = new Usercontest();

            $newUsercontest->user_id = $user->id;
            $newUsercontest->team_id = $team->id;
            $newUsercontest->contest_id = $contest->id;
            $newUsercontest->transaction_id = $request['transaction_id'];
            $newUsercontest->save();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return $newUsercontest;
    }


}

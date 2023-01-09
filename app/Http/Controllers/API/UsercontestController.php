<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usercontest\CreateUsercontestRequest;
use App\Http\Resources\Usercontest\UsercontestByFixtureResource;
use App\Http\Resources\Usercontest\UsercontestLeaderboardResource;
use App\Repositories\UsercontestRepository;
use Illuminate\Http\Request;

class UsercontestController extends Controller
{
    public function __construct(UsercontestRepository $usercontestRepository)
    {

        $this->usercontestRepository = $usercontestRepository;
    }

    public function getUsercontestsByFixture($user_id, $fixture_id)
    {
        $usercontests = $this->usercontestRepository->getConstestsByFixture($user_id, $fixture_id);

        return UsercontestByFixtureResource::collection($usercontests);
    }

    public function getContestTeamDetail(Request $request)
    {
        $contestScorecards = $this->usercontestRepository->getContestTeamScorecards($request->query('user_id'), $request->query('contest_id'));
        return response()->json($contestScorecards);
    }

    public function getPlayerScorecard(Request $request)
    {
        $playerscorecard = $this->usercontestRepository->getScorecardByPlayer($request->query('usercontest_id'), $request->query('player_id'));

        return response()->json($playerscorecard);
    }

    public function getUsercontestsById(Request $request)
    {
        $usercontest = $this->usercontestRepository->getUsercontestsById($request->query('user_id'), $request->query('contest_id'));

        return new UsercontestByFixtureResource($usercontest);
    }

    public function getUsercontestsRankingById(Request $request)
    {
        $ranking = $this->usercontestRepository->getUsercontestsRankingById($request->query('contest_id'));

        return UsercontestLeaderboardResource::collection($ranking);
    }

    public function getUserUpcomingContests($user_id)
    {

        $usercontests = $this->usercontestRepository->getUserUpcomingContests($user_id);

        return UsercontestByFixtureResource::collection($usercontests);
    }

    public function getUserOngoingContests($user_id)
    {

        $usercontests = $this->usercontestRepository->getUserOngoingContests($user_id);

        return UsercontestByFixtureResource::collection($usercontests);
    }

    public function getUserCompletedContests($user_id)
    {

        $usercontests = $this->usercontestRepository->getUserCompletedContests($user_id);

        return UsercontestByFixtureResource::collection($usercontests);
    }

    public function createUsercontest(CreateUsercontestRequest $request)
    {

        $usercontest = $this->usercontestRepository->createUsercontest($request->validated());

        return response()->json($usercontest, 201);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fixture\CreateFixtureRequest;
use App\Http\Requests\Fixture\UpdateFixtureRequest;
use App\Repositories\FixtureRepository;
use Illuminate\Http\Request;

class FixtureController extends Controller
{

    public function __construct(FixtureRepository $fixtureRepository)
    {
        $this->fixtureRepository = $fixtureRepository;
    }
    public function createFixture(CreateFixtureRequest $request) {
        $match = $this->fixtureRepository->storeMatch($request->validated());

        return response()->json($match, 201);
    }

    public function updateFixture(UpdateFixtureRequest $request) {
        $match = $this->fixtureRepository->updateMatch($request->validated());

        return response()->json($match, 201);
    }

    public function getFixtures() {
        $matches = $this->fixtureRepository->getAllMatch();

        return response()->json($matches, 200);
    }
    public function getSingleFixture($match_id) {
        $match = $this->fixtureRepository->getMatch($match_id);

        return response()->json($match, 200);
    }
    public function getUpcomingFixtures() {
        $match = $this->fixtureRepository->getUpcomingMatches();

        return response()->json($match, 200);
    }
    public function getUpcomingFixturesByUser($user_id) {
        $match = $this->fixtureRepository->getUpcomingMatchesByUser($user_id);

        return response()->json($match, 200);
    }

    public function getRunningFixturesByUser($user_id) {
        $match = $this->fixtureRepository->getRunningMatchesByUser($user_id);

        return response()->json($match, 200);
    }

    public function getCompleteFixturesByUser($user_id) {
        $match = $this->fixtureRepository->getCompleteMatchesByUser($user_id);

        return response()->json($match, 200);
    }
}

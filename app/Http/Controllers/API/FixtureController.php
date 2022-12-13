<?php

namespace App\Http\Controllers\API;

use App\Handlers\CricApiDataProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fixture\CreateFixtureRequest;
use App\Http\Requests\Fixture\UpdateFixtureRequest;
use App\Repositories\FixtureRepository;
use Illuminate\Http\Request;

class FixtureController extends Controller {

    public function __construct(FixtureRepository $fixtureRepository) {
        $this->fixtureRepository = $fixtureRepository;
    }

    public function createFixture(CreateFixtureRequest $request) {
        $fixture = $this->fixtureRepository->storeFixture($request->validated());

        return response()->json($fixture, 201);
    }

    public function updateFixture(UpdateFixtureRequest $request) {
        $fixture = $this->fixtureRepository->updateFixture($request->validated());

        return response()->json($fixture, 200);
    }

    public function getUpcomingFixturesForAdmin() {
        $fixtures = (new CricApiDataProvider())->fetchUpcomingFixtures()->toCollection()->sortBy('starting_at');
        return response()->json($fixtures);
    }

    public function getFixtureDetailForTest($fixture_id) {
        $fixture = (new CricApiDataProvider())->fetchFixtureInfo($fixture_id, [
            'include' => 'localteam,visitorteam,lineup',
        ]);
        return response()->json($fixture);
    }

    public function getFixtures(Request $request) {
        $fixturees = $this->fixtureRepository->getAllFixture($request->query('status') ?? null);
        return response()->json($fixturees, 200);
    }

    public function getSingleFixture($fixture_id) {
        $fixture = $this->fixtureRepository->getFixture($fixture_id);

        return response()->json($fixture, 200);
    }

    public function getUpcomingFixtures() {
        $fixture = $this->fixtureRepository->getUpcomingFixturees();

        return response()->json($fixture, 200);
    }

    public function getUpcomingFixturesByUser($user_id) {
        $fixture = $this->fixtureRepository->getUpcomingFixtureesByUser($user_id);
        return response()->json($fixture, 200);
    }

    public function getRunningFixturesByUser($user_id) {
        $fixture = $this->fixtureRepository->getRunningFixtureesByUser($user_id);

        return response()->json($fixture, 200);
    }

    public function getCompleteFixturesByUser($user_id) {
        $fixture = $this->fixtureRepository->getCompleteFixtureesByUser($user_id);

        return response()->json($fixture, 200);
    }
}

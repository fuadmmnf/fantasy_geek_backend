<?php

namespace App\Http\Controllers\API;

use App\Handlers\CricApiDataProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fixture\CreateFixtureRequest;
use App\Http\Requests\Fixture\UpdateFixtureRequest;
use App\Repositories\FixtureRepository;
use Illuminate\Http\Request;

<<<<<<< HEAD
class FixtureController extends Controller {

    public function __construct(FixtureRepository $fixtureRepository) {
        $this->fixtureRepository = $fixtureRepository;
    }

    public function createFixture(CreateFixtureRequest $request) {
=======
class FixtureController extends Controller
{

    public function __construct(FixtureRepository $fixtureRepository)
    {
        $this->fixtureRepository = $fixtureRepository;
    }

    public function createFixture(CreateFixtureRequest $request)
    {
>>>>>>> master
        $fixture = $this->fixtureRepository->storeFixture($request->validated());

        return response()->json($fixture, 201);
    }

<<<<<<< HEAD
    public function updateFixture(UpdateFixtureRequest $request) {
        $fixture = $this->fixtureRepository->updateFixture($request->validated());

        return response()->json($fixture, 200);
    }

    public function getUpcomingFixturesForAdmin() {
        $fixtures = (new CricApiDataProvider())->fetchUpcomingFixtures()->toCollection()->sortBy('starting_at');
        return response()->json($fixtures);
    }

    public function getFixtureDetailForTest($fixture_id) {
=======
    public function updateFixture(UpdateFixtureRequest $request)
    {
        $fixture = $this->fixtureRepository->updateFixture($request->validated());
        if (!$fixture) {
            return response()->json('player ratings not set', 403);
        }
        return response()->json($fixture, 201);
    }

    public function getUpcomingFixturesForAdmin()
    {
        $fixtures = (new CricApiDataProvider())->fetchUpcomingFixtures();
        return response()->json($fixtures);
    }

    public function getFixtureDetailForTest($fixture_id)
    {
>>>>>>> master
        $fixture = (new CricApiDataProvider())->fetchFixtureInfo($fixture_id, [
            'include' => 'localteam,visitorteam,lineup',
        ]);
        return response()->json($fixture);
    }

<<<<<<< HEAD
    public function getFixtures(Request $request) {
        $fixturees = $this->fixtureRepository->getAllFixture($request->query('status') ?? null);
        return response()->json($fixturees, 200);
    }

    public function getSingleFixture($fixture_id) {
=======
    public function getFixtures()
    {
        $fixturees = $this->fixtureRepository->getAllFixture();

        return response()->json($fixturees, 200);
    }

    public function getSingleFixture($fixture_id)
    {
>>>>>>> master
        $fixture = $this->fixtureRepository->getFixture($fixture_id);

        return response()->json($fixture, 200);
    }

<<<<<<< HEAD
    public function getUpcomingFixtures() {
=======
    public function getUpcomingFixtures()
    {
>>>>>>> master
        $fixture = $this->fixtureRepository->getUpcomingFixturees();

        return response()->json($fixture, 200);
    }

<<<<<<< HEAD
    public function getUpcomingFixturesByUser($user_id) {
=======
    public function getUpcomingFixturesByUser($user_id)
    {
>>>>>>> master
        $fixture = $this->fixtureRepository->getUpcomingFixtureesByUser($user_id);
        return response()->json($fixture, 200);
    }

<<<<<<< HEAD
    public function getRunningFixturesByUser($user_id) {
=======
    public function getRunningFixturesByUser($user_id)
    {
>>>>>>> master
        $fixture = $this->fixtureRepository->getRunningFixtureesByUser($user_id);

        return response()->json($fixture, 200);
    }

<<<<<<< HEAD
    public function getCompleteFixturesByUser($user_id) {
=======
    public function getCompleteFixturesByUser($user_id)
    {
>>>>>>> master
        $fixture = $this->fixtureRepository->getCompleteFixtureesByUser($user_id);

        return response()->json($fixture, 200);
    }
}

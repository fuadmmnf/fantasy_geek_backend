<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\UpdateMatchRequest;
use App\Repositories\MatchRepository;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function __construct(MatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }
    public function createMatch(CreateMatchRequest $request) {
        $match = $this->matchRepository->storeMatch($request->validated());

        return response()->json($match, 201);
    }

    public function updateMatch(UpdateMatchRequest $request) {
        $match = $this->matchRepository->updateMatch($request->validated());

        return response()->json($match, 201);
    }

    public function getMatches() {
        $matches = $this->matchRepository->getAllMatch();

        return response()->json($matches, 200);
    }
    public function getSingleMatch($match_id) {
        $match = $this->matchRepository->getMatch($match_id);

        return response()->json($match, 200);
    }
    public function getUpcomingMatches() {
        $match = $this->matchRepository->getUpcomingMatches();

        return response()->json($match, 200);
    }
    public function getUpcomingMatchesByUser($user_id) {
        $match = $this->matchRepository->getUpcomingMatchesByUser($user_id);

        return response()->json($match, 200);
    }

    public function getRunningMatchesByUser($user_id) {
        $match = $this->matchRepository->getRunningMatchesByUser($user_id);

        return response()->json($match, 200);
    }

    public function getCompleteMatchesByUser($user_id) {
        $match = $this->matchRepository->getCompleteMatchesByUser($user_id);

        return response()->json($match, 200);
    }
}

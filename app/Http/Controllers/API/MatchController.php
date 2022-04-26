<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Match\CreateMatchRequest;
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
}

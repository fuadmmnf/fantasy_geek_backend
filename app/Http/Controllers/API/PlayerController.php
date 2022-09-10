<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\CreatePlayerRequest;
use App\Repositories\PlayerRepository;

class PlayerController extends Controller
{
    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }
    public function createPlayer(CreatePlayerRequest $request) {
        $match = $this->playerRepository->storePlayer($request->validated());

        return response()->json($match, 201);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\CreatePlayerRequest;
use App\Http\Requests\Player\UpdateRatingsRequest;
use App\Repositories\PlayerRepository;
use function Symfony\Component\Translation\t;

class PlayerController extends Controller
{
    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function getUnratedPlayers(){
        $unratedPlayers = $this->playerRepository->getUnrated();
        return response()->json($unratedPlayers);
    }
    public function createPlayer(CreatePlayerRequest $request) {
        $match = $this->playerRepository->storePlayer($request->validated());

        return response()->json($match, 201);
    }

    public function updatePlayerRatings(UpdateRatingsRequest $request){
        $this->playerRepository->updatePlayerRatings($request->validated());
        return response()->noContent();
    }
}

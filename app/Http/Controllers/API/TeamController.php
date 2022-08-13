<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Repositories\TeamRepository;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }
    public function createTeam(CreateTeamRequest $request) {
        $team = $this->teamRepository->storeTeam($request->validated());

        return response()->json($team, 201);
    }

    public function getSingleTeam($team_id) {
        $team = $this->teamRepository->getTeam($team_id);

        return response()->json($team, 200);
    }
}

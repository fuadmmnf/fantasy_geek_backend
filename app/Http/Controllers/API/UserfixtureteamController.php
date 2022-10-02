<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Userfixtureteams\CreateUserfixtureteamRequest;
use App\Http\Resources\Userfixtureteam\UserfixtureteamsByFixtureResource;
use App\Repositories\UserfixtureteamRepository;
use Illuminate\Http\Request;

class UserfixtureteamController extends Controller
{
    public function __construct(UserfixtureteamRepository $userfixtureteamRepository)
    {
        $this->userfixtureteamRepository = $userfixtureteamRepository;
    }

    public function getUserfixtureteamsByFixture(Request $request){
        $userteams = $this->userfixtureteamRepository->getUserFixtureTeams($request->query('user_id'), $request->query('fixture_id'));

        return UserfixtureteamsByFixtureResource::collection($userteams);
    }
    public function createUserfixtureteam(CreateUserfixtureteamRequest $request){
        $userfixtureteam = $this->userfixtureteamRepository->createUserFixtureTeam($request->validated());

        return response()->json($userfixtureteam, 201);
    }


}

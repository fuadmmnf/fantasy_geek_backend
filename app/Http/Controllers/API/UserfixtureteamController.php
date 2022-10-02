<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Userfixtureteams\CreateUserfixtureteamRequest;
use App\Repositories\UserfixtureteamRepository;
use Illuminate\Http\Request;

class UserfixtureteamController extends Controller
{
    public function __construct(UserfixtureteamRepository $userfixtureteamRepository)
    {
        $this->userfixtureteamRepository = $userfixtureteamRepository;
    }

    public function createUserfixtureteam(CreateUserfixtureteamRequest $request){
        $userfixtureteam = $this->userfixtureteamRepository->createUserFixtureTeam($request->validated());

        return response()->json($userfixtureteam, 201);
    }


}

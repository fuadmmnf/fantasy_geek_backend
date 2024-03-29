<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\CreateContestRequest;
use App\Http\Requests\Contest\UpdateContestRequest;
use App\Http\Resources\Contest\ContestDetailResource;
use App\Http\Resources\Contest\ContestsByFixtureResource;
use App\Repositories\ContestRepository;
use Illuminate\Http\Request;

class ContestController extends Controller
{

    public function __construct(ContestRepository $contestRepository)
    {
        $this->contestRepository = $contestRepository;
    }

<<<<<<< HEAD
    public function test(Request $request){
        return response()->json('congrats');
    }
=======
>>>>>>> master
    public function getContestsByFixture(Request $request)
    {
        $contests = $this->contestRepository->getContestsByFixture($request->query('fixture_id'));
        return ContestsByFixtureResource::collection($contests);
    }

    public function getContestDetails($contest_id){
        $contest = $this->contestRepository->getDetail($contest_id);
        return new ContestDetailResource($contest);
    }

    public function createContest(CreateContestRequest $request) {
        $contest = $this->contestRepository->saveContest($request->validated());
        return response()->json($contest, 201);
    }

    public function updateContest(UpdateContestRequest $request) {
        $contest = $this->contestRepository->updateContest($request->validated());
        return response()->json($contest, 201);
    }


}

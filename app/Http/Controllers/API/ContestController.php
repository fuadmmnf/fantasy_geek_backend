<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\CreateContestRequest;
use App\Http\Resources\Contest\ContestDetailResource;
use App\Http\Resources\Contest\ContestsByMatchResource;
use App\Repositories\ContestRepository;
use Illuminate\Http\Request;

class ContestController extends Controller
{

    public function __construct(ContestRepository $contestRepository)
    {
        $this->contestRepository = $contestRepository;
    }

    public function getContestsByMatch(Request $request)
    {
        $contests = $this->contestRepository->getContestsByMatch($request->query('match_id'));
        return ContestsByMatchResource::collection($contests);
    }

    public function getContestDetails(Request $request){
        $contest = $this->contestRepository->getDetail($request->query('contest_id'));
        return new ContestDetailResource($contest);
    }

    public function store(CreateContestRequest $request) {
        $contest = $this->contestRepository->saveContest($request->validated());
        return response()->json($contest, 201);
    }


}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\CreateContestRequest;
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


        $contestsByMatch = $contestsByMatch->map(function ($contest) use ($contestIdsByUser) {
            return [
                'id' => $contest->id,
                'name' => $contest->name,
                'totalPrize' => $contest->total_award_amount,
                'entryFee' => $contest->entry_fee,
                'entryCapacity' => $contest->entry_capacity,
                'entryCount' => $contest->entry_count,
                'firstPrize' => $contest->award_amount,
                'winnerCount' => $contest->winner_count,
                'prizeList' => json_decode($contest->prize_list, true)
            ];
        });

        return response()->json([
            'match' => $match,
            'contests' => $contestsByMatch
        ]);
    }


    public function store(CreateContestRequest $request) {
        $contest = $this->contestRepository->saveContest($request->validated());
        return response()->json($contest, 201);
    }


}

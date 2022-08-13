<?php

namespace App\Repositories;

use App\Models\Contest;
use Illuminate\Support\Facades\DB;

class ContestRepository
{
    public function getContestsByMatch($match_id){
        $contestsByMatch = Contest::where('match_id', $match_id)->get();
        return $contestsByMatch;
    }

    public function getDetail($contest_id){
        $contest = Contest::findOrFail('id', $contest_id);
        return $contest;
    }

    public function saveContest(array $request)
    {
        DB::beginTransaction();
        try{
            $newContest = new Contest();
            $newContest->name = $request['name'];
            $newContest->match_id = $request['match_id'];
            $newContest->entry_fee = $request['entry_fee'];
            $newContest->winner_count = $request['winner_count'];
            $newContest->award_amount = $request['award_amount'];
            $newContest->prize_list = json_encode($request['prize_list']);
            $newContest->total_award_amount = $request['total_award_amount'];
            $newContest->entry_capacity = $request['entry_capacity'];
            $newContest->user_standings = json_encode([]);
            $newContest->save();
        } catch (\Exception $exception){
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return $newContest;
    }
}

<?php

namespace App\Repositories;

use App\Models\Contest;
use Illuminate\Support\Facades\DB;

class ContestRepository
{
    public function getContestsByFixture($fixture_id){
        $contestsByFixture = Contest::where('fixture_id', $fixture_id)->get();
        return $contestsByFixture;
    }

    public function getDetail($contest_id){
        $contest = Contest::findOrFail($contest_id);
        return $contest;
    }

    public function saveContest(array $request)
    {
        DB::beginTransaction();
        try{
            $newContest = new Contest();
            $newContest->name = $request['name'];
            $newContest->fixture_id = $request['fixture_id'];
            $newContest->entry_fee = $request['entry_fee'];
            $newContest->winner_count = $request['winner_count'];
            $newContest->award_amount = $request['award_amount'];
            $newContest->prize_list = $request['prize_list'];
            $newContest->total_award_amount = $request['total_award_amount'];
            $newContest->entry_capacity = $request['entry_capacity'];
            $newContest->user_standings = [];
            $newContest->save();
        } catch (\Exception $exception){
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return $newContest;
    }

    public function updateContest(array $request)
    {
        $contest = Contest::findOrFail($request['id']);

        $contest->name = $request['name'];
        $contest->fixture_id = $request['fixture_id'];
        $contest->entry_fee = $request['entry_fee'];
        $contest->winner_count = $request['winner_count'];
        $contest->award_amount = $request['award_amount'];
        $contest->prize_list = $request['prize_list'];
        $contest->total_award_amount = $request['total_award_amount'];
        $contest->entry_capacity = $request['entry_capacity'];
        $contest->user_standings = [];
        $contest->save();


        return $contest;
    }
}

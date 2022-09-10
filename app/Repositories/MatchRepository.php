<?php

namespace App\Repositories;

use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Models\Contest;
use App\Models\Match;
use App\Models\Pointdistribution;
use App\Models\Team;
use App\Models\Usercontest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;

class MatchRepository
{

    public function getAllMatch() {
        $matches = Match::with('team1', 'team2')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $matches;
    }
    public function getMatch($match_id) {
        $match = Match::findOrFail($match_id);
        $match->load('team1', 'team2');

        return $match;
    }

    public function getUpcomingMatches() {
        $matches = Match::where('status', 0)
            ->with('team1', 'team2')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $matches;
    }

    public function getUpcomingMatchesByUser($user_id) {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $matchIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('match_id');

        $userUpcomingMatches = Match::whereIn('id', $matchIdsByContest)
            ->where('status', 0)
            ->with('team1', 'team2')
            ->get();

       return $userUpcomingMatches;
    }

    public function getRunningMatchesByUser($user_id) {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $matchIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('match_id');

        $userRunningMatches = Match::whereIn('id', $matchIdsByContest)
            ->where('status', 1)
            ->with('team1', 'team2')
            ->get();

        return $userRunningMatches;
    }
    public function getCompleteMatchesByUser($user_id) {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $matchIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('match_id');

        $userCompleteMatches = Match::whereIn('id', $matchIdsByContest)
            ->where('status', 2)
            ->with('team1', 'team2')
            ->get();

        return $userCompleteMatches;
    }

    public function storeMatch(array $request)
    {
        $searchMatch = Match::where('api_matchid', $request['api_matchid'])->first();
        if ($searchMatch) {
            return response()->json('Match already created', 400);
        }

        $pointdistribution = Pointdistribution::findOrFail($request['pointdistribution_id']);
        $team1 = Team::findOrFail($request['team1_id']);
        $team2 = Team::findOrFail($request['team2_id']);

        $newMatch = new Match();
        $newMatch->name = $request['name'];
        $newMatch->pointdistribution_id = $pointdistribution->id;
        $newMatch->team1_id = $team1->id;
        $newMatch->team2_id = $team2->id;
        $newMatch->starting_time = Carbon::parse($request['starting_time']);
        $newMatch->api_matchid = $request['api_matchid'];

        if (isset($request['team1_monogram'])) {
            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
            $filename = 'team1_' . time() . '.' . explode(';', explode('/', $request['team1_monogram'])[1])[0];
            $location = public_path('/images/teams/' . $filename);
            Image::make($request['team1_monogram'])->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);
            $newMatch->team1_monogram = $filename;
        }

        if (isset($request['team2_monogram'])) {
            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
            $filename = 'team2_' . time() . '.' . explode(';', explode('/', $request['team2_monogram'])[1])[0];
            $location = public_path('/images/teams/' . $filename);
            Image::make($request['team2_monogram'])->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);
            $newMatch->team2_monogram = $filename;
        }
        $newMatch->save();

        $cricketScorecardHandler = new CricketScorecardUpdater();
        $cricketScorecardHandler->initPlayerScorecardForMatch($newMatch);

        return $newMatch;
    }

    public function updateMatch(array $request) {
        $match = Match::findOrFail($request['id']);
        if(isset($request['starting_time'])){
            $match->starting_time = $request['starting_time'];
        }
        if(isset($request['status'])){
            $match->status = $request['status'];
        }

        $match->save();
        return $match;
    }
}

<?php

namespace App\Repositories;

use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Models\Match;
use App\Models\Pointdistribution;
use App\Models\Team;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;

class MatchRepository
{

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

        if ($request['team1_monogram']) {
            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
            $filename = 'team1_' . time() . '.' . explode(';', explode('/', $request['team1_monogram'])[1])[0];
            $location = public_path('/images/teams/' . $filename);
            Image::make($request['team1_monogram'])->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);
            $newMatch->team1_monogram = $filename;
        }

        if ($request['team2_monogram']) {
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
}

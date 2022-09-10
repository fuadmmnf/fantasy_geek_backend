<?php

namespace App\Repositories;

use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Models\Contest;
use App\Models\Fixture;
use App\Models\Pointdistribution;
use App\Models\Team;
use App\Models\Usercontest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;

class FixtureRepository
{

    public function getAllFixture() {
        $fixturees = Fixture::with('team1', 'team2')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $fixturees;
    }
    public function getFixture($fixture_id) {
        $fixture = Fixture::findOrFail($fixture_id);
        $fixture->load('team1', 'team2');

        return $fixture;
    }

    public function getUpcomingFixturees() {
        $fixturees = Fixture::where('status', 0)
            ->with('team1', 'team2')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $fixturees;
    }

    public function getUpcomingFixtureesByUser($user_id) {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userUpcomingFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 0)
            ->with('team1', 'team2')
            ->get();

       return $userUpcomingFixturees;
    }

    public function getRunningFixtureesByUser($user_id) {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userRunningFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 1)
            ->with('team1', 'team2')
            ->get();

        return $userRunningFixturees;
    }
    public function getCompleteFixtureesByUser($user_id) {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userCompleteFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 2)
            ->with('team1', 'team2')
            ->get();

        return $userCompleteFixturees;
    }

    public function storeFixture(array $request)
    {
        $searchFixture = Fixture::where('api_fixtureid', $request['api_fixtureid'])->first();
        if ($searchFixture) {
            return response()->json('Fixture already created', 400);
        }

        $pointdistribution = Pointdistribution::findOrFail($request['pointdistribution_id']);
        $team1 = Team::findOrFail($request['team1_id']);
        $team2 = Team::findOrFail($request['team2_id']);

        $newFixture = new Fixture();
        $newFixture->name = $request['name'];
        $newFixture->pointdistribution_id = $pointdistribution->id;
        $newFixture->team1_id = $team1->id;
        $newFixture->team2_id = $team2->id;
        $newFixture->starting_time = Carbon::parse($request['starting_time']);
        $newFixture->api_fixtureid = $request['api_fixtureid'];


        if (isset($request['team1_monogram'])) {
            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
            $filename = 'team1_' . time() . '.' . explode(';', explode('/', $request['team1_monogram'])[1])[0];
            $location = public_path('/images/teams/' . $filename);
            Image::make($request['team1_monogram'])->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);
            $newFixture->team1_monogram = $filename;
        }


        if (isset($request['team2_monogram'])) {
            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
            $filename = 'team2_' . time() . '.' . explode(';', explode('/', $request['team2_monogram'])[1])[0];
            $location = public_path('/images/teams/' . $filename);
            Image::make($request['team2_monogram'])->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);
            $newFixture->team2_monogram = $filename;
        }

        $newFixture->save();

        $cricketScorecardHandler = new CricketScorecardUpdater();
        $cricketScorecardHandler->initPlayerScorecardForFixture($newFixture);

        return $newFixture;
    }


    public function updateFixture(array $request) {
        $fixture = Fixture::findOrFail($request['id']);
        if(isset($request['starting_time'])){
            $fixture->starting_time = $request['starting_time'];
        }
        if(isset($request['status'])){
            $fixture->status = $request['status'];
        }

        $fixture->save();
        return $fixture;
    }
}

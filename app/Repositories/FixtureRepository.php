<?php

namespace App\Repositories;

use App\Data\PlayerDTO;
use App\Data\TeamDetailDTO;
use App\Handlers\CricApiDataProvider;
use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Models\Contest;
use App\Models\Fixture;
use App\Models\Player;
use App\Models\Pointdistribution;
use App\Models\Team;
use App\Models\Usercontest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\DataCollection;

class FixtureRepository
{

    public function getAllFixture()
    {
        $fixturees = Fixture::with('team1', 'team2')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $fixturees;
    }

    public function getFixture($fixture_id)
    {
        $fixture = Fixture::findOrFail($fixture_id);
        $fixture->load('team1', 'team2');

        return $fixture;
    }

    public function getUpcomingFixturees()
    {
        $fixturees = Fixture::where('status', 0)
            ->with('team1', 'team2')
            ->orderBy('starting_time', 'DESC')
            ->paginate(20);
        return $fixturees;
    }

    public function getUpcomingFixtureesByUser($user_id)
    {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userUpcomingFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 0)
            ->with('team1', 'team2')
            ->get();

        return $userUpcomingFixturees;
    }

    public function getRunningFixtureesByUser($user_id)
    {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userRunningFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 1)
            ->with('team1', 'team2')
            ->get();

        return $userRunningFixturees;
    }

    public function getCompleteFixtureesByUser($user_id)
    {
        $contestIdsByUser = Usercontest::where('user_id', $user_id)->pluck('contest_id');
        $fixtureIdsByContest = Contest::whereIn('id', $contestIdsByUser)->pluck('fixture_id');

        $userCompleteFixturees = Fixture::whereIn('id', $fixtureIdsByContest)
            ->where('status', 2)
            ->with('team1', 'team2')
            ->get();

        return $userCompleteFixturees;
    }

    private function createTeam(TeamDetailDTO $detailDTO, DataCollection $lineup, int $api_teamid): Team
    {
        //create player entities if not available
        $teammember_ids = $lineup->toCollection()->filter(function (PlayerDTO $playerDTO) use ($api_teamid) {
            return $playerDTO->lineup->team_id == $api_teamid;
        })->map(function (PlayerDTO $playerDTO) {
            $player = Player::where('api_pid', $playerDTO->id)->first();
            if (!$player) {
                $player = (new PlayerRepository())->storePlayer([
                    'playerposition_id' => $playerDTO->position->id, // id's are synced with our backend, via seeder
                    'name' => $playerDTO->fullname,
                    'api_pid' => $playerDTO->id,
                    'image' => $playerDTO->image_path
                ]);
            }
            return $player->id;
        })->all();

        return (new TeamRepository())->storeTeam([
            'name' => $detailDTO->name,
            'type' => 0,
            'image' => $detailDTO->image_path, // same image to local later
            'team_members' => $teammember_ids,
        ]);
    }

    public function storeFixture(array $request)
    {
        $searchFixture = Fixture::where('api_fixtureid', $request['api_fixtureid'])->first();
        if ($searchFixture) {
            return response()->json('Fixture already created', 400);
        }

        $fixtureDetailDTO = (new CricApiDataProvider())->fetchFixtureInfo($request['api_fixtureid'], [
            'include' => 'localteam,visitorteam,lineup',
        ]);


        $newFixture = new Fixture();
        $newFixture->name = `${fixtureDetailDTO->localteam->name}_${fixtureDetailDTO->visitorteam->name}`;
        $newFixture->pointdistribution_id = Pointdistribution::findOrFail($request['pointdistribution_id'])->id;
        $newFixture->team1_id = $this->createTeam($fixtureDetailDTO->localteam, $fixtureDetailDTO->lineup, $fixtureDetailDTO->localteam_id)->id;
        $newFixture->team2_id = $this->createTeam($fixtureDetailDTO->visitorteam, $fixtureDetailDTO->lineup, $fixtureDetailDTO->visitorteam_id)->id;
        $newFixture->starting_time = Carbon::parse($fixtureDetailDTO->starting_at);
        $newFixture->api_fixtureid = $fixtureDetailDTO->id;


//        if (isset($request['team1_monogram'])) {
//            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
//            $filename = 'team1_' . time() . '.' . explode(';', explode('/', $request['team1_monogram'])[1])[0];
//            $location = public_path('/images/teams/' . $filename);
//            Image::make($request['team1_monogram'])->resize(200, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->save($location);
//            $newFixture->team1_monogram = $filename;
//        }
//
//
//        if (isset($request['team2_monogram'])) {
//            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
//            $filename = 'team2_' . time() . '.' . explode(';', explode('/', $request['team2_monogram'])[1])[0];
//            $location = public_path('/images/teams/' . $filename);
//            Image::make($request['team2_monogram'])->resize(200, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->save($location);
//            $newFixture->team2_monogram = $filename;
//        }

        $newFixture->save();
//
//        $cricketScorecardHandler = new CricketScorecardUpdater();
//        $cricketScorecardHandler->initPlayerScorecardForFixture($newFixture);

        return $newFixture;
    }


    public function updateFixture(array $request)
    {
        $fixture = Fixture::findOrFail($request['id']);
        if (isset($request['starting_time'])) {
            $fixture->starting_time = $request['starting_time'];
        }
        if (isset($request['status'])) {
            $fixture->status = $request['status'];
        }

        $fixture->save();
        return $fixture;
    }
}

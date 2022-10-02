<?php

namespace App\Repositories;

use App\Models\Contest;
use App\Models\Fixture;
use App\Models\Team;
use App\Models\User;
use App\Models\Usercontest;
use App\Models\Userfixtureteam;
use Illuminate\Support\Facades\DB;

class UserfixtureteamRepository
{

    public function createUserFixtureTeam(array $request){
        $fixture = Fixture::findOrFail($request['fixture_id']);
        $user = User::findOrFail($request['user_id']);
        $team = Team::findOrFail($request['team_id']);

        $newUserFixtureTeam = new Userfixtureteam();
        $newUserFixtureTeam->user_id = $user->id;
        $newUserFixtureTeam->fixture_id = $fixture->id;
        $newUserFixtureTeam->team_id = $team->id;
        $newUserFixtureTeam->save();

        return $newUserFixtureTeam;
    }


}

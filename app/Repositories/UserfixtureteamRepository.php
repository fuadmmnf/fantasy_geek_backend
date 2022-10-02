<?php

namespace App\Repositories;

use App\Models\Fixture;
use App\Models\Team;
use App\Models\User;
use App\Models\Userfixtureteam;

class UserfixtureteamRepository
{

    public function getUserFixtureTeams($user_id, $fixture_id){
        $teamIds =  Userfixtureteam::where('user_id', $user_id)
            ->where('fixture_id', $fixture_id)
            ->pluck('team_id');

        $teams = Team::whereIn('id', $teamIds)->get();

        return $teams;
    }
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

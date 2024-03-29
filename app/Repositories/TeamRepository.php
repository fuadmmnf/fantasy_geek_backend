<?php

namespace App\Repositories;

use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Models\Player;
use App\Models\Pointdistribution;
use App\Models\Team;
use App\Models\Userfixtureteam;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;

class TeamRepository
{

    function getTeam($team_id) {
        $team = Team::findOrfail($team_id);
//        $team->team_members = $team->team_members, true);
//        $team->key_members = json_decode($team->team_members, true);

//        for ($i = 0; $i < count($team->team_members); $i++) {
//            $player = Player::findOrFail($team->team_members[$i]['id']);
//            $team->team_members[$i]['image'] = $player->image;
//        }


        return $team;
    }

    public function storeTeam(array $request)
    {
        $newTeam = new Team();
        $newTeam->type = $request['type'];
        $newTeam->name = $request['name'];
        $newTeam->image = $request['image']?? null;
        $newTeam->key_members = (count($request['key_members']) == 0) ? null : $request['key_members'];

        $teamMembers = [];
        foreach ($request['team_members'] as $teamMember) {
            $player = Player::findOrFail($teamMember);
            $teamMembers[] = [
                "id" => $player->id,
                "name" => $player->name,
                "pid" => $player->api_pid,
                "rating" => $player->rating,
                "playerposition_id" => $player->playerposition_id,
                "image" => $player->image,

            ];
        }
        $newTeam->team_members = $teamMembers;
        do {
            $code = Str::random(16);
            $team_code = Team::where('code', $code)->first();
        } while ($team_code);
        $newTeam->code = $code;

        $newTeam->save();

        if($request['type'] == 1 && isset($request['fixture_id'])){
            $userfixtureteam = new Userfixtureteam();
            $userfixtureteam->fixture_id = $request['fixture_id'];
            $userfixtureteam->team_id = $newTeam->id;
            $userfixtureteam->user_id = $request['user_id'];
            $userfixtureteam->save();
        }

        return $newTeam;
    }
}

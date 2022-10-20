<?php

namespace App\Repositories;

use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Models\Player;
use App\Models\Pointdistribution;
use App\Models\Team;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;

class TeamRepository
{

    function getTeam($team_id) {
        $team = Team::findOrfail($team_id);
        $decodedTeams = json_decode($team);
        $decodedTeams->team_members = json_decode($decodedTeams->team_members, true);
        $decodedTeams->key_members= json_decode($decodedTeams->key_members, true);

        for ($i = 0; $i < count($decodedTeams->team_members); $i++) {
            $player = Player::findOrFail($decodedTeams->team_members[$i]['id']);
            $decodedTeams->team_members[$i]['image'] = $player->image;
        }

        return $decodedTeams;
    }

    public function storeTeam(array $request)
    {
        $newTeam = new Team();
        $newTeam->type = $request['type'];
        $newTeam->name = $request['name'];
//        $newTeam->image = $request['image'];
        $newTeam->key_members = (count($request['key_members']) == 0) ? null : $request['key_members'];

        $teamMembers = [];
        foreach ($request['team_members'] as $teamMember) {
            $player = Player::findOrFail($teamMember);
            $teamMembers[] = [
                "id" => $player->id,
                "name" => $player->name,
                "pid" => $player->api_pid,
                "rating" => $player->rating,
                "playerposition_id" => $player->playerposition_id
            ];
        }
        $newTeam->team_members = $teamMembers;
        do {
            $code = Str::random(16);
            $team_code = Team::where('code', $code)->first();
        } while ($team_code);
        $newTeam->code = $code;

        $newTeam->save();

        return $newTeam;
    }
}

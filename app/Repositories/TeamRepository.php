<?php

namespace App\Repositories;

use App\Handlers\Scorecard\CricketScorecardUpdater;
use App\Models\Match;
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
        $team->team_members = json_decode($team->team_members, true);
        $team->key_members = json_decode($team->team_members, true);

        for ($i = 0; $i < count($team->team_members); $i++) {
            $player = Player::findOrFail($team->team_members[$i]['id']);
            $team->team_members[$i]['image'] = $player->image;
        }

        return $team;
    }

    public function storeTeam(array $request)
    {
        $newTeam = new Team();
        $newTeam->type = $request['type'];
        $newTeam->name = $request['name'];
        $newTeam->key_members = (count($request['key_members']) == 0) ? null : json_encode($request['key_members']);

        $teamMembers = [];
        foreach ($request['team_members'] as $teamMember) {
            $player = Player::findOrFail($teamMember['id']);
            $teamMembers[] = [
                "id" => $player->id,
                "name" => $player->name,
                "pid" => $player->api_pid,
                "rating" => $player->rating,
                "playerposition_id" => $player->playerposition_id
            ];
        }
        $newTeam->team_members = json_encode($teamMembers);
        do {
            $code = Str::random(16);
            $team_code = Team::where('code', $code)->first();
        } while ($team_code);
        $newTeam->code = $code;

        $newTeam->save();

        return $newTeam;
    }

}

<?php

namespace App\Http\Resources\Userfixtureteam;

use App\Http\Resources\Contest\ContestsByFixtureResource;
use App\Http\Resources\Player\PlayerForTeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Userfixtureteam */
class UserfixtureteamsByFixtureResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'key_members' => $this->key_members,
<<<<<<< HEAD
            'team_members' =>  $this->team_members,
=======
            'team_members' =>  PlayerForTeamResource::collection(json_decode($this->team_members)),
>>>>>>> master
        ];
    }
}

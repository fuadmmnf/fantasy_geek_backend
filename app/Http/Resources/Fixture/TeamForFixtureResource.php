<?php

namespace App\Http\Resources\Fixture;

use App\Http\Resources\Contest\ContestsByFixtureResource;
use App\Http\Resources\Player\PlayerForTeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Userfixtureteam */
class TeamForFixtureResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'code' => $this->code,
            'key_members' => $this->key_members,
            'team_members' =>  PlayerForTeamResource::collection(json_decode($this->team_members)),
            'image' => $this->image,
        ];
    }
}

<?php

namespace App\Http\Resources\Fixture;

use App\Http\Resources\Contest\ContestsByFixtureResource;
use App\Http\Resources\Player\PlayerForTeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Userfixtureteam */
class FixtureResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pointdistribution_id' => $this->pointdistribution_id,
            'team1_id' => $this->team1_id,
            'team2_id' => $this->team2_id,
            'status' => $this->status,
            'name' => $this->name,
            'starting_time' => $this->starting_time,
            'team1' => new TeamForFixtureResource(json_decode($this->team1)),
            'team2' => new TeamForFixtureResource(json_decode($this->team2)),
        ];
    }
}

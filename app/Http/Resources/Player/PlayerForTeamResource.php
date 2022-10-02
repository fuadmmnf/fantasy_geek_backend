<?php

namespace App\Http\Resources\Player;

use App\Http\Resources\Contest\ContestsByFixtureResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Player */
class PlayerForTeamResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'playerposition_id' => $this->playerposition_id,
            'name' => $this->name,
            'rating' => $this->rating,
            'code' => $this->code,
            'image' => $this->image,
        ];
    }
}

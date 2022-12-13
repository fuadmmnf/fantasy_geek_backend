<?php

namespace App\Http\Resources\Usercontest;

use App\Http\Resources\Contest\ContestsByFixtureResource;
use App\Http\Resources\User\PublicUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Usercontest */
class UsercontestByFixtureResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
//            'user_id' => $this->user_id,
//            'team_id' => $this->team_id,
//            'contest_id' => $this->contest_id,
//            'contest' => new ContestsByFixtureResource($this->contest)
            'user' => new PublicUserResource($this->user),
            'key_members' => $this->when(),
            'score' => $this->score,
            'ranking' => $this->ranking,
            'team_stats' => $this->team_stats,
            'contest' => new ContestsByFixtureResource($this->contest)
        ];
    }
}

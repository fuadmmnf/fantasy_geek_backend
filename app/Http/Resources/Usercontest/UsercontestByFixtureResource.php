<?php

namespace App\Http\Resources\Usercontest;

use App\Http\Resources\Contest\ContestsByFixtureResource;
<<<<<<< HEAD
use App\Http\Resources\User\PublicUserResource;
=======
>>>>>>> master
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
<<<<<<< HEAD
//            'user_id' => $this->user_id,
//            'team_id' => $this->team_id,
//            'contest_id' => $this->contest_id,
//            'contest' => new ContestsByFixtureResource($this->contest)
            'user' => new PublicUserResource($this->user),
            'key_members' => $this->team->key_members,
            'score' => $this->score,
            'ranking' => $this->ranking,
            'team_stats' => $this->team_stats,
=======
            'user_id' => $this->user_id,
            'team_id' => $this->team_id,
            'contest_id' => $this->contest_id,
>>>>>>> master
            'contest' => new ContestsByFixtureResource($this->contest)
        ];
    }
}

<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Contest\ContestsByFixtureResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class PublicUserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'image' => $this->image
        ];
    }
}

<?php

namespace App\Http\Resources\Contest;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Contest */
class ContestsByMatchResource extends JsonResource {
	/**
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request) {
		return [

		];
	}
}

<?php

namespace App\Http\Resources\Contest;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Contest */
class ContestsByFixtureResource extends JsonResource {
	/**
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request) {
		return [
            'id' => $this->id,
<<<<<<< HEAD
            'match_id' => $this->match_id,
=======
            'fixture_id' => $this->fixture_id,
>>>>>>> master
            'name' => $this->name,
            'totalPrize' => $this->total_award_amount,
            'entryFee' => $this->entry_fee,
            'entryCapacity' => $this->entry_capacity,
            'entryCount' => $this->entry_count,
            'firstPrize' => $this->award_amount,
            'winnerCount' => $this->winner_count,
            'prizeList' => $this->prize_list
		];
	}
}

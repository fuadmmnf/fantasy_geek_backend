<?php

namespace App\Http\Requests\Contest;

use Illuminate\Foundation\Http\FormRequest;

class CreateContestRequest extends FormRequest {
	public function rules(): array {
		return [
            'name' => 'required',
            'match_id' => 'required',
            'entry_fee' => 'required',
            'winner_count' => 'required',
            'award_amount' => 'required',
            'prize_list' => 'required',
            'total_award_amount' => 'required',
            'entry_capacity' => 'required',
		];
	}

	public function authorize(): bool {
		return true;
	}
}

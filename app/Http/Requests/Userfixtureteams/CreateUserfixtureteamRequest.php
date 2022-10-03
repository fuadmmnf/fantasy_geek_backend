<?php

namespace App\Http\Requests\Userfixtureteams;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserfixtureteamRequest extends FormRequest {
	public function rules(): array {
		return [
            'fixture_id' => 'required',
            'user_id' => 'required',
            'team_id' => 'required',
		];
	}

	public function authorize(): bool {
		return true;
	}
}

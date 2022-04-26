<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;

class CreateMatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required| min:1',
            'pointdistribution_id' => 'required| numeric',
            'team1_id' => 'required| numeric',
            'team2_id' => 'required| numeric',
            'starting_time' => 'required',
            'api_matchid' => 'required',
            'team1_monogram' => 'sometimes',
            'team2_monogram' => 'sometimes'
        ];
    }
}

<?php

namespace App\Http\Requests\Usercontest;

use Illuminate\Foundation\Http\FormRequest;

class CreateUsercontestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'contest_id' => 'required',
            'team_id' => 'required',
            'transaction_id' => 'required',
        ];
    }
}

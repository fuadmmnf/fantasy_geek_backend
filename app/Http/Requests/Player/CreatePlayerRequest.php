<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerRequest extends FormRequest
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
            'playerposition_id' => 'required| numeric',
            'name' => 'required',
            'api_pid' => 'required',
            'rating' => 'required| numeric',
            'image' => 'sometimes',
        ];
    }
}

<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'player_ratings' => 'required|array',
        ];
    }

    public function authorize()
    {
        return true;
    }
}

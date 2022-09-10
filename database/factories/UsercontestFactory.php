<?php

namespace Database\Factories;

use App\Models\Usercontest;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsercontestFactory extends Factory
{
    protected $model = Usercontest::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'contest_id' => rand (1,10),
            'team_id' => 3,
            'transaction_id' => 123,
            'captain_id' => 7,
            'vicecaptain_id' => 34
        ];
    }
}

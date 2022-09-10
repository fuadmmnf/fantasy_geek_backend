<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */


    public function definition()
    {
        $teams = ["BAN", "IND", "SL", "PAK", "AFG", "AUS", "ENG", "NZ", "SA", "WI", "ZIM", "IRE"];
        return [
            'name' => $teams[rand(0, count($teams) - 1)],
            'type' => 0,
            'code' => $this->faker->unique()->ean8
        ];
    }
}

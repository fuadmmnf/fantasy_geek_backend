<?php

namespace Database\Factories;

use App\Models\Contest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContestFactory extends Factory
{
    protected $model = Contest::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'match_id' => rand(1,3),
            'name' => 'Demo Contest',
            'entry_fee' => rand(500, 10000),
            'winner_count' => rand(20,25),
            'award_amount' => rand(20, 300),
            'total_award_amount' =>  rand(500,1000),
            'entry_capacity' => 100
        ];
    }
}

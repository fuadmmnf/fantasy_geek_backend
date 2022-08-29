<?php

namespace Database\Factories;

use App\Models\Match;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchFactory extends Factory
{
    protected $model = Match::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pointdistribution_id' => 2,
            'team1_id' => rand(1,3),
            'team2_id' => rand(4,5),
            'api_matchid' => $this->faker->unique()->ean8,
            'status' => 0,
            'name' => 'Demo Match',
            'team1_monogram' => 'team1_monogram',
            'team2_monogram' => 'team2_monogram',
            'starting_time' => Carbon::now()
        ];
    }
}

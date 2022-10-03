<?php

namespace Database\Factories;

use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class FixtureFactory extends Factory
{
    protected $model = Fixture::class;
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
            'api_fixtureid' => $this->faker->unique()->ean8,
            'status' => 0,
            'name' => 'Demo Fixture',
//            'team1_monogram' => 'team1_monogram',
//            'team2_monogram' => 'team2_monogram',
            'starting_time' => Carbon::now()
        ];
    }
}

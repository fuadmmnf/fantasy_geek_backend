<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'playerposition_id' => rand(1,4),
            'name' => $this->faker->name,
            'api_pid' => $this->faker->uuid,
            'rating' => rand(1,1),
            'code' => '017' . $this->faker->unique()->ean8
        ];
    }
}

<?php

namespace Database\Seeders;
use App\Models\Match;
use App\Models\Player;
use App\Models\Playerposition;
use App\Models\Team;
use Illuminate\Database\Seeder;

class LocalMockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $players = Player::factory()->count(40)->create([
            'playerposition_id' => rand(1,4)
        ]);

        $teams = Team::factory()->count(5)->create([
            'key_members' => null,
            'team_members' => json_encode(Player::all()->random(15))
        ]);

        $matches = Match::factory()->count(5)->create([
            'team1_id' => rand(1,4),
            'team2_id' => rand(2,5),
        ]);

    }
}

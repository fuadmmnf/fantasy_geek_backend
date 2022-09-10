<?php

namespace Database\Seeders;
use App\Models\Contest;
use App\Models\Match;
use App\Models\Player;
use App\Models\Playerposition;
use App\Models\Team;
use App\Models\Usercontest;
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
//        $players = Player::factory()->count(40)->create();
//
//        $teams = Team::factory()->count(5)->create([
//            'key_members' => null,
//            'team_members' => json_encode(Player::all()->random(15))
//        ]);
//
//        $matches = Match::factory()->count(5)->create();

//        $contests = Contest::factory()->count(10)->create();

        $usercontests = Usercontest::factory()->count(3)->create();
    }
}

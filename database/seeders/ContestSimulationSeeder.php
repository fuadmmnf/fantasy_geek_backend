<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\User;
use App\Repositories\ContestRepository;
use App\Repositories\FixtureRepository;
use App\Repositories\TeamRepository;
use App\Repositories\UsercontestRepository;
use Illuminate\Database\Seeder;

class ContestSimulationSeeder extends Seeder
{
    public function run()
    {
        $fixture = (new FixtureRepository())->storeFixture(['api_fixtureid' => 39929, 'pointdistribution_id' => 1]);
        $contestInfos = [
            [
                'name' => 'demo contest',
                'fixture_id' => $fixture->id,
                'entry_fee' => 20,
                "winner_count" => 5,
                "award_amount" => 50,
                "prize_list" => [50, 20, 15, 10, 5],
                "total_award_amount" => 100,
                "entry_capacity" => 50
            ]
        ];

        $contest = (new ContestRepository())->saveContest($contestInfos[0]);
        $users = User::findMany(range(1, 7));
        $players = Player::all();
        foreach ($users as $user){
            $team_players = $players->random(11);
            $team = (new TeamRepository())->storeTeam([
                'name' => "team {$user->id}",
                'type' => 1,
                'image' => null,
                'team_members' => $team_players->pluck('id')->toArray(),
                'key_members' => ['captain' => $team_players->random()->id, 'vicecaptain' => $team_players->random()->id],
            ]);
            (new UsercontestRepository())->createUsercontest([
                'user_id' => $user->id,
                'contest_id' => $contest->id,
                'team_id' => $team->id,
                'transaction_id' => rand()
            ]);
        }
    }
}

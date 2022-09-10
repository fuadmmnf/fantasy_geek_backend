<?php

namespace Database\Seeders;
use App\Models\Playerposition;
use Illuminate\Database\Seeder;

class PlayerpositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $cricketpositions = [
            [
                'name' => 'ar',
                'fullname' => 'All-Rounder',
                'limit_min' => 1,
                'limit_max' => 4,
                'message' => "Pick 1 - 4 All-Rounders"
            ],
            [
                'name' => 'wk',
                'fullname' => 'Wicket-Keeper',
                'limit_min' => 1,
                'limit_max' => 4,
                'message' => "Pick 1 - 4 Wicket-Keepers"


            ],
            [
                'name' => 'bat',
                'fullname' => 'Batsman',
                'limit_min' => 3,
                'limit_max' => 6,
                'message' => "Pick 3 - 6 Batsmen"
            ],
            [
                'name' => 'bwl',
                'fullname' => 'Bowler',
                'limit_min' => 3,
                'limit_max' => 6,
                'message' => "Pick 3 - 6 Bowlers"
            ],

        ];

        foreach ($cricketpositions as $key => $value) {
            Playerposition::create($value);
        }
    }
}

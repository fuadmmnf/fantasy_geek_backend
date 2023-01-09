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
                'id' => 4,
                'name' => 'ar',
                'fullname' => 'Allrounder',
                'limit_min' => 1,
                'limit_max' => 4,
                'message' => "Pick 1 - 4 All-Rounders"
            ],
            [
                'id' => 3,
                'name' => 'wk',
                'fullname' => 'Wicketkeeper',
                'limit_min' => 1,
                'limit_max' => 4,
                'message' => "Pick 1 - 4 Wicket-Keepers"


            ],
            [
                'id' => 1,
                'name' => 'bat',
                'fullname' => 'Batsman',
                'limit_min' => 3,
                'limit_max' => 6,
                'message' => "Pick 3 - 6 Batsmen"
            ],
            [
                'id' => 2,
                'name' => 'bwl',
                'fullname' => 'Bowler',
                'limit_min' => 3,
                'limit_max' => 6,
                'message' => "Pick 3 - 6 Bowlers"
            ],

        ];

        foreach ($cricketpositions as $key => $value) {
            $pp = new Playerposition();
            $pp->id = $value['id'];
            $pp->name = $value['name'];
            $pp->fullname = $value['fullname'];
            $pp->limit_min = $value['limit_min'];
            $pp->limit_max = $value['limit_max'];
            $pp->message = $value['message'];
            $pp->save();
        }
    }
}

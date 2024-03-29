<?php

namespace Database\Seeders;
use App\Models\Pointdistribution;
use Illuminate\Database\Seeder;

class PointdistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $t20PointDistribution = array(
            'is_in_starting_xi' => 4,
            'runs' => 1,
            'four_x' => 1,
            'six_x' => 2,
            'duck' => -2,
            'half_century' => 4,
            'century' => 8,
            'econ_rate' => json_encode(array( // stored for val <= range than key
                '4' => 6,
                '5' => 4,
                '6' => 2,
                '10' => -2,
                '11' => -4,
                '60' => -6
            )),
            'strike_rate' => json_encode(array(
                '70' => -2,
                '60' => -4,
                '50' => -8,
            )),
            'wickets_1' => 25,
            'wicket_3' => 6,
            '4wicket_4' => 8,
            'wicket_5' => 16,
            'maiden_overs' => 8,
            'run_outs' => 12,
            'catches_stumpings' => 12,
//            'catches' => 8,
        );
        $t20distribution = new Pointdistribution();
        $t20distribution->fixture_type = 't20';
        $t20distribution->distribution = json_encode($t20PointDistribution);
        $t20distribution->save();




        $odPointDistribution = array(
            'is_in_starting_xi' => 4,
            'runs' => 1,
            'four_x' => 1,
            'six_x' => 2,
            'duck' => -3,
            'runs_50' => 4,
            'runs_100' => 8,
            'econ_rate' => json_encode(array(
                '2.5' => 6,
                '3.5' => 4,
                '4.5' => 2,
                '8' => -2,
                '9' => -4,
                '60' => -6
            )),
            'strike_rate' => json_encode(array(
                '60' => -2,
                '50' => -4,
                '40' => -8,
            )),
            'wickets_1' => 25,
            'wickets_3' => 4,
            'wickets_4' => 6,
            'wickets_5' => 8,
            'maiden_overs' => 6,
            'run_outs' => 12,
            'catches_stumpings' => 12,
//            'catches' => 8,
        );

        $oddistribution = new Pointdistribution();
        $oddistribution->fixture_type = 'od';
        $oddistribution->distribution = json_encode($odPointDistribution);
        $oddistribution->save();




        $testPointDistribution = array(
            'is_in_starting_xi' => 4,
            'runs' => 1,
            'four_x' => 1,
            'six_x' => 2,
            'duck' => -6,
            'runs_50' => 4,
            'runs_100' => 8,
            'econ_rate' => json_encode(array(
                '2.5' => 6,
                '3.5' => 4,
                '4.5' => 2,
                '8' => -2,
                '9' => -4,
                '60' => -6
            )),
            'strike_rate' => json_encode(array(
                '60' => -2,
                '50' => -4,
                '40' => -8,
            )),
            'wickets_1' => 18,
            'wickets_3' => 4,
            'wickets_4' => 6,
            'wickets_5' => 8,
            'maiden_overs' => 1,
            'run_outs' => 12,
            'catches_stumpings' => 12,
//            'catches' => 8,
        );

        $testdistribution = new Pointdistribution();
        $testdistribution->fixture_type = 'test';
        $testdistribution->distribution = json_encode($testPointDistribution);
        $testdistribution->save();

    }
}

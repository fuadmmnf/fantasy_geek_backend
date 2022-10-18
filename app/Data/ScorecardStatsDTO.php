<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ScorecardStatsDTO extends Data {
    public function __construct(
        public int $is_in_starting_xi = 0,
        public int $runs = 0,
        public int $runs_50 = 0,
        public int $runs_100 = 0,
        public int $four_x = 0,
        public int $six_x = 0,
        public int $duck = 0,
        public float $econ_rate = 0,
        public float $strike_rate = 0,
        public int $wickets_1 = 0,
        public int $wickets_3 = 0,
        public int $wickets_4 = 0,
        public int $wickets_5 = 0,
        public int $maiden_overs = 0,
        public int $run_outs = 0,
        public int $catches_stumpings = 0,
//        public int $catches = 0,

    ){}
}

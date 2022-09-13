<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class PointDistributionDTO extends Data {
    public function __construct(
        public int $is_in_starting_xi,
        public int $runs,
        public int $runs_50,
        public int $runs_100,
        public int $four_x,
        public int $six_x,
        public int $duck,
        public int $econ_rate,
        public int $strike_rate,
        public int $wickets_1,
        public int $wickets_3,
        public int $wickets_4,
        public int $wickets_5,
        public int $maiden_overs,
        public int $run_outs,
        public int $stumpings,
        public int $catches,

    ){}
}

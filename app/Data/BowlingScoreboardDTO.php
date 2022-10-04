<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BowlingScoreboardDTO extends Data {
    public function __construct(
        public int $player_id,
        public float $overs=0,
        public int $medians=0,
        public int $runs=0,
        public int $wickets=0,
        public int $wide=0,
        public int $noball=0,
        public float $rate=0,
    ){}
}

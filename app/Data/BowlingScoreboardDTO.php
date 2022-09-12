<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BowlingScoreboardDTO extends Data {
    public function __construct(
        public int $player_id,
        public float $overs,
        public int $medians,
        public int $runs,
        public int $wickets,
        public int $wide,
        public int $noball,
        public float $rate,
    ){}
}

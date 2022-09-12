<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BattingScoreboardDTO extends Data {
    public function __construct(
        public int $player_id,
        public int $ball,
        public int $score_id,
        public int $score,
        public int $four_x,
        public int $six_x,
        public float $fow_score,
        public float $fow_balls,
        public float $rate,
    ){}
}

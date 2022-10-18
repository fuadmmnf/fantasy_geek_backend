<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class BattingScoreboardDTO extends Data {
    public function __construct(
        public int $player_id,
        public int $ball=0,
        public int $score_id=0,
        public int $score=0,
        public int $four_x=0,
        public int $six_x=0,
        public float $fow_score=0,
        public float $fow_balls=0,
        public float $rate=0,
        public ?int $catch_stump_player_id = -1,
        public ?int $bowling_player_id = -1,
        public ?int $runout_by_id = -1,
    ){

    }
}

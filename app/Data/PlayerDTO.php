<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class PlayerDTO extends Data {
    public function __construct(
        public int $id,
        public string $fullname,
        public string $image_path,
        public string $battingstyle,
        public string $bowlingstyle,
        public PlayerPositionDTO $position,
        public TeamLineupDTO $lineup,
    ){}
}

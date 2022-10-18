<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class TeamLineupDTO extends Data {
    public function __construct(
        public int $team_id,
        public bool $captain,
        public bool $wicketkeeper,
        public bool $substitution,
    ){}
}

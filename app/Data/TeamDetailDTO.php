<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

//use Spatie\LaravelData\DataCollection;

class TeamDetailDTO extends Data {
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public string $image_path,
        /** @var \App\Data\PlayerDTO[] */
        public ?DataCollection $squad,
    ) {
    }
}

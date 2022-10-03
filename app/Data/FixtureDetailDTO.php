<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class FixtureDetailDTO extends Data {
    public function __construct(
        public int $id,
        public string $round,
        public int $localteam_id,
        public int $visitorteam_id,
        public ?int $toss_won_team_id,
        public CarbonImmutable $starting_at,
        public string $type,
        public string $status,
        public string $note,
        public ?int $winner_team_id,
        public ?int $man_of_match_id,
        public ?TeamDetailDTO $visitorteam,
        public ?TeamDetailDTO $localteam,
        #[DataCollectionOf(PlayerDTO::class)]
        public ?DataCollection $lineup,
        #[DataCollectionOf(BowlingScoreboardDTO::class)]
        public ?DataCollection $bowling,
        #[DataCollectionOf(BattingScoreboardDTO::class)]
        public ?DataCollection $batting,
    ) {
    }
}

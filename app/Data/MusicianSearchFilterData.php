<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Max;

class MusicianSearchFilterData extends Data
{
    public function __construct(
        public ?string $search,
        #[Numeric]
        #[Min(0)]
        public ?int $minPrice,
        #[Numeric]
        #[Min(0)]
        #[Max(1000)]
        public ?int $maxPrice,
        #[Numeric]
        public ?float $latitude,
        #[Numeric]
        public ?float $longitude,
        #[Numeric]
        #[Min(1)]
        public ?int $distance,
        public ?array $selectedGenres,
        public ?string $genreMatch,
        public ?array $selectedEventTypes,
        public ?string $eventTypeMatch,
    ) {}
}

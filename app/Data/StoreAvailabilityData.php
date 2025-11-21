<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Rule;

class StoreAvailabilityData extends Data
{
    public function __construct(
        #[Rule('required|date')]
        public string $unavailable_date,
        #[Rule('nullable|string|max:255')]
        public ?string $reason,
    ) {}
}

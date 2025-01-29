<?php

namespace App\Domain\ViewRequest;

use Carbon\Carbon;

class CreateViewRequestDto
{
    public function __construct(
        public Carbon $date,
        public int $propertyId,
        public ?int $userId = null,
    )
    {
    }
}

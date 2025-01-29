<?php

namespace App\Domain\Developer;

class UpdateDeveloperDto
{
    public function __construct(
        public int $id,
        public ?string $address = null,
        public ?string $name = null,
        public ?float $rating = null,
        public ?string $email = null,
    )
    {
    }
}

<?php

namespace App\Domain\Developer;

class DeveloperEntity
{
    public function __construct(
        public ?int $id,
        public string $address,
        public string $name,
        public ?float $rating,
        public string $email,
    )
    {
    }
}

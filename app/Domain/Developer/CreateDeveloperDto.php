<?php

namespace App\Domain\Developer;

readonly class CreateDeveloperDto
{
    public function __construct(
        public string $address,
        public string $name,
        public float $rating,
        public string $email,
    )
    {
    }
}

<?php

namespace App\Domain\Developer;

readonly class GetDevelopersDto
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 10,
        public ?string $name = null,
        public ?string $sortRating = null,
    )
    {
    }
}

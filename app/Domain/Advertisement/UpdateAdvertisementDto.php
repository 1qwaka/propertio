<?php

namespace App\Domain\Advertisement;

readonly class UpdateAdvertisementDto
{
    public function __construct(
        public int $id,
        public ?string $description = null,
        public ?int $price = null,
        public ?string $type = null,
        public ?bool $hidden = null,
    )
    {
    }
}

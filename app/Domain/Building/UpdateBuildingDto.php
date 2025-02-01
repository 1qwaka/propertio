<?php

namespace App\Domain\Building;

class UpdateBuildingDto
{
    public function __construct(
        public int $id,
        public ?int $typeId = null,
        public ?bool $hotWater = null,
        public ?bool $gas = null,
        public ?int $elevators = null,
        public ?int $floors = null,
        public ?int $buildYear = null,
        public ?int $developerId = null,
        public ?string $address = null,
    )
    {
    }
}

<?php

namespace App\Domain\Building;

readonly class CreateBuildingDto
{
    public function __construct(
        public int $typeId,
        public int $floors,
        public int $buildYear,
        public int $developerId,
        public string $address,
        public ?bool $hotWater = null,
        public ?bool $gas = null,
        public ?int $elevators = null,
    )
    {
    }
}

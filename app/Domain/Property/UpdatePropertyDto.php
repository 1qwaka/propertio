<?php

namespace App\Domain\Property;

readonly class UpdatePropertyDto
{
    public function __construct(
        public ?string $renovation = null,
        public ?int $floor = null,
        public ?int $area = null,
        public ?int $floorTypeId = null,
        public ?string $address = null,
        public ?LivingSpaceType $livingSpaceType = null,
    )
    {
    }
}

<?php

namespace App\Domain\Property;

class UpdatePropertyDto
{
    public function __construct(
        public int $id,
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

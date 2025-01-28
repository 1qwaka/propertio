<?php

namespace App\Domain\Property;

readonly class PropertiesPageDto
{
    public function __construct(
        public int $total,
        public int $current,
        public int $perPage,
        public array $items,
    )
    {
    }
}

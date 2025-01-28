<?php

namespace App\Domain\Advertisement;

class AdvertisementPageDto
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

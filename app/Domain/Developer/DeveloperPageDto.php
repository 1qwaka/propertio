<?php

namespace App\Domain\Developer;

class DeveloperPageDto
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

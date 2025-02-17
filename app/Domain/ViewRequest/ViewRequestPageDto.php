<?php

namespace App\Domain\ViewRequest;

class ViewRequestPageDto
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

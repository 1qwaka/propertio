<?php

namespace App\Domain\ViewRequest;

class GetViewRequestDto
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 10,
        public ?int $userOrAgentId = null,
    )
    {
    }
}

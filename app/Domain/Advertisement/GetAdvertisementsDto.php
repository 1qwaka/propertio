<?php

namespace App\Domain\Advertisement;

readonly class GetAdvertisementsDto
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 10,
        public ?int $agentId = null,
        public ?bool $hidden = false,
    )
    {
    }
}

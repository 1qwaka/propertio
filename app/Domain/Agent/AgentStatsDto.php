<?php

namespace App\Domain\Agent;

class AgentStatsDto
{
    public function __construct(
        public int $count,
    )
    {
    }
}

<?php

namespace App\Domain\Agent;

interface IAgentRepository
{
    public function getTypes(): array;

    public function findByUser(int $id): AgentEntity;

//    public function getStats(int $id): AgentStatsDto;

    public function create(CreateAgentDto $data): AgentEntity;

    public function update(UpdateAgentDto $data): AgentEntity;
}

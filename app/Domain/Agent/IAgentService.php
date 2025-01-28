<?php

namespace App\Domain\Agent;

interface IAgentService
{
    public function getSelf(): AgentEntity;

    public function getTypes(): array;

    public function getStats(): array;

    public function create(CreateAgentDto $data): AgentEntity;

    public function update(UpdateAgentDto $data): AgentEntity;

}

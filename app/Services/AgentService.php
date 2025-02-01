<?php

namespace App\Services;

use App\Domain\Agent\AgentEntity;
use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\IAgentRepository;
use App\Domain\Agent\IAgentService;
use App\Domain\Agent\UpdateAgentDto;

class AgentService implements IAgentService
{
    public function __construct(
        private readonly IAgentRepository $repository,
        private readonly UserService $userService,
    )
    {
    }

    public function getSelf(): AgentEntity
    {
        $userId = $this->userService->getSelf()->id;
        return $this->repository->findByUser($userId);
    }

    public function getTypes(): array
    {
        return $this->repository->getTypes();
    }

//    public function getStats(): AgentStatsDto
//    {
//        return $this->repository->getStats($this->getSelf()->id);
//    }

    public function create(CreateAgentDto $data): AgentEntity
    {
        $data->userId = $this->userService->getSelf()->id;
        return $this->repository->create($data);
    }

    public function update(UpdateAgentDto $data): AgentEntity
    {
        $data->id = $this->getSelf()->id;
        return $this->repository->update($data);
    }
}

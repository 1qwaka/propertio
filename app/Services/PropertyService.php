<?php

namespace App\Services;

use App\Domain\Agent\IAgentService;
use App\Domain\Property\CreatePropertyDto;
use App\Domain\Property\GetPropertiesDto;
use App\Domain\Property\IPropertyRepository;
use App\Domain\Property\IPropertyService;
use App\Domain\Property\LivingSpaceType;
use App\Domain\Property\PropertiesPageDto;
use App\Domain\Property\PropertyEntity;
use App\Domain\Property\UpdatePropertyDto;
use App\Exceptions\WithErrorCodeException;


class PropertyService implements IPropertyService
{
    public function __construct(
        private readonly IPropertyRepository $repository,
        private readonly IAgentService $agentService,
    )
    {
    }

    public function getFloorTypes(): array
    {
        return $this->repository->getFloorTypes();
    }

    public function getSpaceTypes(): array
    {
        return [LivingSpaceType::PRIMARY, LivingSpaceType::SECONDARY];
    }

    public function create(CreatePropertyDto $data): PropertyEntity
    {
        $data->agentId = $this->agentService->getSelf()->id;
        return $this->repository->create($data);
    }

    public function find(int|string $id): PropertyEntity
    {
        return $this->repository->find($id);
    }

    public function get(GetPropertiesDto $data): PropertiesPageDto
    {
        return $this->repository->get($data);
    }

    public function update(UpdatePropertyDto $data): PropertyEntity
    {
        if (!$this->canEdit($data->id)) {
            throw new WithErrorCodeException('You don\'t have access to edit this property', 403);
        }
        return $this->repository->update($data);
    }

    public function delete(int|string $id): void
    {
        if (!$this->canEdit($id)) {
            throw new WithErrorCodeException('You don\'t have access to edit this property', 403);
        }
        $this->repository->delete($id);
    }

    private function canEdit(int $id): bool {
        $item = $this->repository->find($id);
        $currentAgentId = $this->agentService->getSelf()->id;
        return $currentAgentId === $item->agentId;
    }
}

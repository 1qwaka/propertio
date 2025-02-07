<?php

namespace App\Services;

use App\Domain\Advertisement\AdvertisementEntity;
use App\Domain\Advertisement\AdvertisementPageDto;
use App\Domain\Advertisement\CreateAdvertisementDto;
use App\Domain\Advertisement\GetAdvertisementsDto;
use App\Domain\Advertisement\IAdvertisementRepository;
use App\Domain\Advertisement\IAdvertisementService;
use App\Domain\Advertisement\UpdateAdvertisementDto;
use App\Domain\Agent\IAgentService;
use App\Domain\Property\IPropertyService;
use App\Exceptions\WithErrorCodeException;

class AdvertisementService implements IAdvertisementService
{

    public function __construct(
        private readonly IAdvertisementRepository $repository,
        private readonly IAgentService $agentService,
        private readonly IPropertyService $propertyService,
    )
    {
    }

    public function create(CreateAdvertisementDto $data): AdvertisementEntity
    {
        $property = $this->propertyService->find($data->propertyId);
        $agent = $this->agentService->getSelf();

        if ($property->agentId != $agent->id) {
            throw new WithErrorCodeException(
                'You don\'t have access to create advertisements with this property',
                403
            );
        }
        return $this->repository->create($data);

        $data->agentId = $agent->id;
        return $this->repository->create($data);
    }

    public function find(int|string $id): AdvertisementEntity
    {
        return $this->repository->find($id);
    }

    public function get(GetAdvertisementsDto $data): AdvertisementPageDto
    {
        // хотим скрытые объявления указанного агента
        if ($data->hidden && $data->agentId) {
            try {
                $agent = $this->agentService->getSelf();
                // не являемся указанным агентом
                if ($data->agentId !== $agent->id) {
                    $data->hidden = false;
                }
            // не являемся агентом в принципе
            } catch (\Exception $e) {
                $data->hidden = false;
            }
        } else {
            $data->hidden = false;
        }

        return $this->repository->get($data);
    }

    public function update(UpdateAdvertisementDto $data): AdvertisementEntity
    {
        if (!$this->canEdit($data->id)) {
            throw new WithErrorCodeException('You don\'t have access to edit this advertisement', 403);
        }
        return $this->repository->update($data);
    }

    public function delete(int|string $id): void
    {
        if (!$this->canEdit($id)) {
            throw new WithErrorCodeException('You don\'t have access to edit this advertisement', 403);
        }
        $this->repository->delete($id);
    }

    private function canEdit(int $id): bool
    {
        $item = $this->repository->find($id);
        $currentAgentId = $this->agentService->getSelf()->id;
        return $item->agentId === $currentAgentId;
    }
}

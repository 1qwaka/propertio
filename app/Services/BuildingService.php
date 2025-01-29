<?php

namespace App\Services;

use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingPageDto;
use App\Domain\Building\CreateBuildingDto;
use App\Domain\Building\IBuildingRepository;
use App\Domain\Building\IBuildingService;
use App\Domain\Building\UpdateBuildingDto;

class BuildingService implements IBuildingService
{
    public function __construct(
        private readonly IBuildingRepository $repository
    )
    {
    }

    public function getTypes(): array
    {
        return $this->repository->getTypes();
    }

    public function create(CreateBuildingDto $data): BuildingEntity
    {
        return $this->repository->create($data);
    }

    public function find(int $id): BuildingEntity
    {
        return $this->repository->find($id);
    }

    public function get(int $page, int $perPage): BuildingPageDto
    {
        return $this->repository->paginate($page, $perPage);
    }

    public function update(UpdateBuildingDto $data): BuildingEntity
    {
        return $this->repository->update($data);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}

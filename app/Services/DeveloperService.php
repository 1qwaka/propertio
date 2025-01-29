<?php

namespace App\Services;

use App\Domain\Developer\CreateDeveloperDto;
use App\Domain\Developer\DeveloperEntity;
use App\Domain\Developer\DeveloperPageDto;
use App\Domain\Developer\GetDevelopersDto;
use App\Domain\Developer\IDeveloperRepository;
use App\Domain\Developer\IDeveloperService;
use App\Domain\Developer\UpdateDeveloperDto;

class DeveloperService implements IDeveloperService
{
    public function __construct(
        private readonly IDeveloperRepository $repository,
    )
    {
    }

    public function create(CreateDeveloperDto $data): DeveloperEntity
    {
        return $this->repository->create($data);
    }

    public function find(int $id): DeveloperEntity
    {
        return $this->repository->find($id);
    }

    public function get(GetDevelopersDto $data): DeveloperPageDto
    {
        return $this->repository->paginate($data);
    }

    public function update(UpdateDeveloperDto $data): DeveloperEntity
    {
        return $this->repository->update($data);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}

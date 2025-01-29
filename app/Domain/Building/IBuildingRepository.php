<?php

namespace App\Domain\Building;

interface IBuildingRepository
{
    public function getTypes(): array;

    public function create(CreateBuildingDto $data): BuildingEntity;

    public function find(int $id): BuildingEntity;

    public function paginate(int $page, int $perPage): BuildingPageDto;

    public function update(UpdateBuildingDto $data): BuildingEntity;

    public function delete(int $id): void;
}

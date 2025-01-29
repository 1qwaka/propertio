<?php

namespace App\Domain\Developer;

interface IDeveloperRepository
{
    public function create(CreateDeveloperDto $data): DeveloperEntity;

    public function find(int $id): DeveloperEntity;

    public function paginate(GetDevelopersDto $data): DeveloperPageDto;

    public function update(UpdateDeveloperDto $data): DeveloperEntity;

    public function delete(int $id): void;
}

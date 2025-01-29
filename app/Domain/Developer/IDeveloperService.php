<?php

namespace App\Domain\Developer;

interface IDeveloperService
{
    public function create(CreateDeveloperDto $data): DeveloperEntity;

    public function find(int $id): DeveloperEntity;

    public function get(GetDevelopersDto $data): DeveloperPageDto;

    public function update(UpdateDeveloperDto $data): DeveloperEntity;

    public function delete(int $id): void;
}

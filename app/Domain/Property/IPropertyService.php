<?php

namespace App\Domain\Property;

interface IPropertyService
{
    public function getFloorTypes(): array;

    public function getSpaceTypes(): array;

    public function create(CreatePropertyDto $data): PropertyEntity;

    public function find(int|string $id): PropertyEntity;

    public function get(GetPropertiesDto $data): PropertiesPageDto;

    public function update(UpdatePropertyDto $data): PropertyEntity;

    public function delete(int|string $id): void;

}

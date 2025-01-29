<?php

namespace App\Domain\Advertisement;

interface IAdvertisementRepository
{
    public function create(CreateAdvertisementDto $data): AdvertisementEntity;

    public function find(int|string $id): AdvertisementEntity;

    public function get(GetAdvertisementsDto $data): AdvertisementPageDto;

    public function update(UpdateAdvertisementDto $data): AdvertisementEntity;

    public function delete(int|string $id): void;
}

<?php

namespace App\Domain\ViewRequest;

use App\Domain\User\UserEntity;

interface IViewRequestRepository
{
    public function create(CreateViewRequestDto $data): ViewRequestEntity;

    public function find(int $id): ViewRequestEntity;

    public function getAgent(GetViewRequestDto $data): ViewRequestPageDto;

    public function getUser(GetViewRequestDto $data): ViewRequestPageDto;

    public function update(UpdateViewRequestDto $data): ViewRequestEntity;

    public function delete(int $id): void;
}

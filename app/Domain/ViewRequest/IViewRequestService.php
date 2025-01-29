<?php

namespace App\Domain\ViewRequest;

use Carbon\Carbon;

interface IViewRequestService
{
    public function create(CreateViewRequestDto $data): ViewRequestEntity;

    public function find(int $id): ViewRequestEntity;

    public function getAgent(GetViewRequestDto $data): ViewRequestPageDto;

    public function getUser(GetViewRequestDto $data): ViewRequestPageDto;

    public function updateDate(int $id, Carbon $date): ViewRequestEntity;

    public function updateStatus(int $id, ViewRequestStatus $status): ViewRequestEntity;

    public function delete(int $id): void;
}

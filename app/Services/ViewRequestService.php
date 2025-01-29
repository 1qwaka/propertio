<?php

namespace App\Services;

use App\Domain\Agent\IAgentService;
use App\Domain\Property\IPropertyService;
use App\Domain\User\IUserService;
use App\Domain\ViewRequest\CreateViewRequestDto;
use App\Domain\ViewRequest\GetViewRequestDto;
use App\Domain\ViewRequest\IViewRequestRepository;
use App\Domain\ViewRequest\IViewRequestService;
use App\Domain\ViewRequest\UpdateViewRequestDto;
use App\Domain\ViewRequest\ViewRequestEntity;
use App\Domain\ViewRequest\ViewRequestPageDto;
use App\Domain\ViewRequest\ViewRequestStatus;
use App\Exceptions\WithErrorCodeException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ViewRequestService implements IViewRequestService
{
    public function __construct(
        private readonly IViewRequestRepository $repository,
        private readonly IUserService $userService,
        private readonly IAgentService $agentService,
        private readonly IPropertyService $propertyService,
    )
    {
    }

    public function create(CreateViewRequestDto $data): ViewRequestEntity
    {
        $data->userId = $this->userService->getSelf()->id;
        return $this->repository->create($data);
    }

    public function find(int $id): ViewRequestEntity
    {
        $request = $this->repository->find($id);
        if (!$this->isRelatedUser($request) && !$this->isRelatedAgent($request)) {
            throw new WithErrorCodeException('You dont have access to view request', 403);
        }
        return $request;
    }

    public function getAgent(GetViewRequestDto $data): ViewRequestPageDto
    {
        $data->userOrAgentId = $this->agentService->getSelf()->id;
        return $this->repository->getAgent($data);
    }

    public function getUser(GetViewRequestDto $data): ViewRequestPageDto
    {
        $data->userOrAgentId = $this->userService->getSelf()->id;
        return $this->repository->getUser($data);
    }

    public function updateDate(int $id, Carbon $date): ViewRequestEntity
    {
        $item = $this->repository->find($id);
        if (!$this->isRelatedUser($item)) {
            throw new WithErrorCodeException('You dont have access to view request', 403);
        }
        if ($item->status !== ViewRequestStatus::OPEN) {
            throw new WithErrorCodeException('Cannot edit accepted/rejected request', 400);
        }
        return $this->repository->update(new UpdateViewRequestDto($id, date: $date));
    }

    public function updateStatus(int $id, ViewRequestStatus $status): ViewRequestEntity
    {
        $item = $this->repository->find($id);
        if (!$this->isRelatedAgent($item)) {
            throw new WithErrorCodeException('You dont have access to view request', 403);
        }
        if ($item->status !== ViewRequestStatus::OPEN) {
            throw new WithErrorCodeException('Cannot edit accepted/rejected request', 400);
        }
        return $this->repository->update(new UpdateViewRequestDto($id, status: $status));
    }

    public function delete(int $id): void
    {
        $item = $this->repository->find($id);
        if (!$this->isRelatedUser($item)) {
            throw new WithErrorCodeException('You dont have access to view request', 403);
        }
        $this->repository->delete($id);
    }

    private function isRelatedAgent(ViewRequestEntity $item): bool
    {
        $agent = $this->agentService->getSelf();
        $property = $this->propertyService->find($item->propertyId);
        return $property->agentId === $agent->id;
    }

    private function isRelatedUser(ViewRequestEntity $item): bool
    {
        $user = $this->userService->getSelf();
        return $user->id === $item->userId;
    }
}

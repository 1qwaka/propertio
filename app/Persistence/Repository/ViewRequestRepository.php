<?php

namespace App\Persistence\Repository;

use App\Domain\User\UserEntity;
use App\Domain\ViewRequest\CreateViewRequestDto;
use App\Domain\ViewRequest\GetViewRequestDto;
use App\Domain\ViewRequest\IViewRequestRepository;
use App\Domain\ViewRequest\UpdateViewRequestDto;
use App\Domain\ViewRequest\ViewRequestEntity;
use App\Domain\ViewRequest\ViewRequestPageDto;
use App\Domain\ViewRequest\ViewRequestStatus;
use App\Exceptions\WithErrorCodeException;
use App\Models\ViewRequest;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Converters\ViewRequestConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ViewRequestRepository implements IViewRequestRepository
{

    public function create(CreateViewRequestDto $data): ViewRequestEntity
    {
        $item = ViewRequest::create([
            'date' => $data->date->toString(),
            'property_id' => $data->propertyId,
            'status' => ViewRequestStatus::OPEN->value,
            'user_id' => $data->userId,
        ]);

        return ViewRequestConverter::toDomain($item);
    }

    public function find(int $id): ViewRequestEntity
    {
        $item = ViewRequest::find($id);
        if (!$item) {
            throw new ModelNotFoundException('View Request not found');
        }
        return ViewRequestConverter::toDomain($item);
    }

    public function getAgent(GetViewRequestDto $data): ViewRequestPageDto
    {
        $pagination = ViewRequest::join('properties', 'view_requests.property_id', '=', 'properties.id')
            ->where('properties.agent_id', $data->userOrAgentId)
            ->paginate(perPage: $data->perPage, page: $data->page);
//            ->select('view_requests.*'); // Если нужно выбрать только поля из view_requests

        return new ViewRequestPageDto(
            $pagination->total(),
            $pagination->currentPage(),
            $pagination->perPage(),
            $pagination->items()
        );
    }

    public function getUser(GetViewRequestDto $data): ViewRequestPageDto
    {
        $pagination = ViewRequest::query()
            ->where('user_id', $data->userOrAgentId)
            ->paginate(perPage: $data->perPage, page: $data->page);

        return new ViewRequestPageDto(
            $pagination->total(),
            $pagination->currentPage(),
            $pagination->perPage(),
            $pagination->items()
        );
    }

    public function update(UpdateViewRequestDto $data): ViewRequestEntity
    {
        $item = ViewRequest::find($data->id);
        if (!$item) {
            throw new ModelNotFoundException('View Request not found');
        }
        $item->update(DtoToModelConverter::toArray($data));
        return ViewRequestConverter::toDomain($item);
    }

    public function delete(int $id): void
    {
        $item = ViewRequest::find($id);
        $item->delete();
    }
}

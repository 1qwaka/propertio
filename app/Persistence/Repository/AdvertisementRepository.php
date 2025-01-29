<?php

namespace App\Persistence\Repository;

use App\Domain\Advertisement\AdvertisementEntity;
use App\Domain\Advertisement\AdvertisementPageDto;
use App\Domain\Advertisement\CreateAdvertisementDto;
use App\Domain\Advertisement\GetAdvertisementsDto;
use App\Domain\Advertisement\IAdvertisementRepository;
use App\Domain\Advertisement\UpdateAdvertisementDto;
use App\Exceptions\WithErrorCodeException;
use App\Models\Advertisement;
use App\Models\Agent;
use App\Persistence\Converters\AdvertisementConverter;
use App\Persistence\Converters\DtoToModelConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdvertisementRepository implements IAdvertisementRepository
{
    public function create(CreateAdvertisementDto $data): AdvertisementEntity
    {
        $advertisement = Advertisement::create(DtoToModelConverter::toArray($data));
        return AdvertisementConverter::toDomain($advertisement);
    }

    public function find(int|string $id): AdvertisementEntity
    {
        $item = Advertisement::find($id);
        if (!$item) {
            throw new ModelNotFoundException('Advertisement not found ZoZ');
        }
        return AdvertisementConverter::toDomain($item);
    }

    public function get(GetAdvertisementsDto $data): AdvertisementPageDto
    {
        $query = Advertisement::query();

        if ($data->agentId) {
            $query->where('agent_id', $data->agentId);
        }

        $page = $query->paginate(perPage: $data->perPage, page: $data->page);
        return new AdvertisementPageDto(
            $page->total(),
            $page->currentPage(),
            $page->perPage(),
            $page->items()
        );
    }

    public function update(UpdateAdvertisementDto $data): AdvertisementEntity
    {
        $advertisement = Advertisement::find($data->id);
        $advertisement->update(DtoToModelConverter::toArray($data));
        return AdvertisementConverter::toDomain($advertisement);
    }

    public function delete(int|string $id): void
    {
        $item = Advertisement::find($id);
        $item->delete();
    }

}

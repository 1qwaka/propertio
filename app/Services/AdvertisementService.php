<?php

namespace App\Services;

use App\Domain\Advertisement\AdvertisementEntity;
use App\Domain\Advertisement\AdvertisementPageDto;
use App\Domain\Advertisement\GetAdvertisementsDto;
use App\Domain\Advertisement\IAdvertisementService;
use App\Domain\Advertisement\UpdateAdvertisementDto;
use App\Domain\Property\CreateAdvertisementDto;
use App\Exceptions\WithErrorCodeException;
use App\Models\Advertisement;
use App\Models\Agent;
use App\Models\Property;
use App\Persistence\Converters\AdvertisementConverter;
use App\Persistence\Converters\DtoToModelConverter;
use Illuminate\Support\Facades\Auth;

class AdvertisementService implements IAdvertisementService
{

    public function create(CreateAdvertisementDto $data): AdvertisementEntity
    {
        $property = Property::find($data->propertyId);
        $agent = Agent::where('user_id', Auth::user()->id)->get()->first();
        if ($property->agent_id != $agent->id) {
            throw new WithErrorCodeException('You don\'t have access to create advertisements with this property', 403);
        }

        $advertisement = Advertisement::create(array_merge(
            DtoToModelConverter::toArray($data),
            [ 'agent_id' => $agent->id ],
        ));

        return AdvertisementConverter::toDomain($advertisement);
    }

    public function find(int|string $id): AdvertisementEntity
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            throw new WithErrorCodeException('Advertisement not found', 404);
        }

        return AdvertisementConverter::toDomain($advertisement);
    }

    public function get(GetAdvertisementsDto $data): AdvertisementPageDto
    {
        $query = Advertisement::query();

        if ($data->hidden && $data->agentId) {
            $agent = Agent::where('user_id', Auth::user()->id)->get()->first();
            if ($data->agentId == $agent->id) {
                $query->where('hidden', true);
            }
        } else {
            $query->where('hidden', false);
        }

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

        if (!$advertisement) {
            throw new WithErrorCodeException('Advertisement not found', 404);
        }

        $agent = Agent::where('user_id', Auth::user()->id)->get()->first();
        if ($advertisement->agent_id != $agent->id) {
            throw new WithErrorCodeException('You don\'t have access to edit this advertisement', 403);
        }

        $advertisement->update(DtoToModelConverter::toArray($data));
        return AdvertisementConverter::toDomain($advertisement);
    }

    public function delete(int|string $id): void
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            throw new WithErrorCodeException('Advertisement not found', 404);
        }

        $agent = Agent::where('user_id', Auth::user()->id)->get()->first();
        if ($advertisement->agent_id != $agent->id) {
            throw new WithErrorCodeException('You don\'t have access to edit this advertisement', 403);
        }

        $advertisement->delete();
    }
}

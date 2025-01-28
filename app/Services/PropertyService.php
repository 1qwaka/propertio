<?php

namespace App\Services;

use App\Domain\Property\CreatePropertyDto;
use App\Domain\Property\GetPropertiesDto;
use App\Domain\Property\IPropertyService;
use App\Domain\Property\PropertiesPageDto;
use App\Domain\Property\PropertyEntity;
use App\Domain\Property\UpdatePropertyDto;
use App\Models\Property;
use App\Persistence\Converters\PropertyConverter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertyService implements IPropertyService
{

    public function getFloorTypes(): array
    {
        $types = DB::table('floor_type')->get();
        return $types->toArray();
    }

    public function getSpaceTypes(): array
    {
        return ['primary', 'secondary'];
    }

    public function create(CreatePropertyDto $data): PropertyEntity
    {
        $item = Property::create([
            'renovation' => $data->renovation,
            'building_id' => $data->buildingId,
            'floor' => $data->floor,
            'area' => $data->area,
            'floor_type_id' => $data->floorTypeId,
            'address' => $data->address,
            'living_space_type' => $data->livingSpaceType,
            'agent_id' => Auth::user()->agent->id,
        ]);

        return PropertyConverter::toDomain($item);
    }

    public function find(int|string $id): PropertyEntity
    {
        return PropertyConverter::toDomain(Property::find($id));
    }

    public function get(GetPropertiesDto $data): PropertiesPageDto
    {
        $query = Property::query();

        if ($data->agentId) {
            $query->where('agent_id', $data->agentId);
        }
        if ($data->livingSpaceType) {
            $query->where('living_space_type', $data->livingSpaceType);
        }

        $page = $query->paginate(perPage: $data->perPage, page: $data->page);
        return new PropertiesPageDto(
            $page->total(),
            $page->currentPage(),
            $page->perPage(),
            $page->items()
        );
    }

    public function getSelf(GetPropertiesDto $data): array
    {
        // TODO: Implement getSelf() method.
    }

    public function update(UpdatePropertyDto $data): PropertyEntity
    {
        // TODO: Implement update() method.
    }

    public function delete(int|string $id): void
    {
        // TODO: Implement delete() method.
    }
}

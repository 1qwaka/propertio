<?php

namespace App\Services;

use App\Domain\Property\CreatePropertyDto;
use App\Domain\Property\GetPropertiesDto;
use App\Domain\Property\IPropertyService;
use App\Domain\Property\PropertiesPageDto;
use App\Domain\Property\PropertyEntity;
use App\Domain\Property\UpdatePropertyDto;
use App\Exceptions\WithErrorCodeException;
use App\Models\Agent;
use App\Models\Property;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Converters\PropertyConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function update(UpdatePropertyDto $data): PropertyEntity
    {
        $property = Property::find($data->id);
        if (!$property) {
            throw new WithErrorCodeException('Property not found', 404);
        }

        $agent = Agent::where('user_id', Auth::user()->id)->get()->first();
        if ($agent->id != $property->agent_id) {
            throw new WithErrorCodeException('You don\'t have access to edit this property', 403);
        }

        $property->update(DtoToModelConverter::toArray($data));
        return PropertyConverter::toDomain($property);
    }

    public function delete(int|string $id): void
    {
        $property = Property::find($id);

        if (!$property) {
            throw new WithErrorCodeException('Property not found', 404);
        }

        $agent = Agent::where('user_id', Auth::user()->id)->get()->first();
        if ($agent->id != $property->agent_id) {
            throw new WithErrorCodeException('You don\'t have access to delete this property', 403);
        }

        $property->delete();
    }
}

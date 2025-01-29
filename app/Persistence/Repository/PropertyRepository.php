<?php

namespace App\Persistence\Repository;

use App\Domain\Property\CreatePropertyDto;
use App\Domain\Property\GetPropertiesDto;
use App\Domain\Property\IPropertyRepository;
use App\Domain\Property\PropertiesPageDto;
use App\Domain\Property\PropertyEntity;
use App\Domain\Property\UpdatePropertyDto;
use App\Models\Property;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Converters\PropertyConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PropertyRepository implements IPropertyRepository
{

    public function getFloorTypes(): array
    {
        return DB::table('floor_type')->get()->toArray();
    }

    public function create(CreatePropertyDto $data): PropertyEntity
    {
        $item = Property::create(DtoToModelConverter::toArray($data));
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
            throw new ModelNotFoundException('Property not found');
        }
        $property->update(DtoToModelConverter::toArray($data));
        return PropertyConverter::toDomain($property);

    }

    public function delete(int|string $id): void
    {
        $property = Property::find($id);
        if (!$property) {
            throw new ModelNotFoundException('Property not found');
        }
        $property->delete();
    }
}

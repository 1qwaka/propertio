<?php

namespace App\Persistence\Repository;

use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingPageDto;
use App\Domain\Building\CreateBuildingDto;
use App\Domain\Building\IBuildingRepository;
use App\Domain\Building\UpdateBuildingDto;
use App\Exceptions\WithErrorCodeException;
use App\Models\Building;
use App\Persistence\Converters\BuildingConverter;
use App\Persistence\Converters\DtoToModelConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class BuildingRepository implements IBuildingRepository
{
    public function __construct(
        private readonly Building $model,
    )
    {
    }

    public function getTypes(): array
    {
        return DB::table('building_type')->get()->toArray();
    }
    public function create(CreateBuildingDto $data): BuildingEntity
    {
        $building = $this->model->newQuery()->create(DtoToModelConverter::toArray($data));
        return BuildingConverter::toDomain($building);
    }

    public function find(int $id): BuildingEntity
    {
        $building = $this->model->newQuery()->find($id);

        if (!$building) {
            throw new ModelNotFoundException('Building not found');
        }

        return BuildingConverter::toDomain($building);
    }

    public function paginate(int $page, int $perPage): BuildingPageDto
    {
        $pagination = $this->model->newQuery()->paginate(perPage: $perPage, page: $page);

        return new BuildingPageDto(
            $pagination->total(),
            $pagination->currentPage(),
            $pagination->perPage(),
            $pagination->items()
        );
    }

    public function update(UpdateBuildingDto $data): BuildingEntity
    {
        $building = $this->model->newQuery()->find($data->id);
        if (!$building) {
            throw new WithErrorCodeException('Building not found', 404);
        }

        $this->model->newQuery()->where('id', $data->id)
            ->update(DtoToModelConverter::toArray($data));

        return BuildingConverter::toDomain($building);
    }

    public function delete(int $id): void
    {
        $building = $this->model->newQuery()->find($id);

        if (!$building) {
            throw new WithErrorCodeException('Building not found', 404);
        }

        $building->delete();
    }
}

<?php

namespace App\Persistence\Repository;

use App\Domain\Developer\CreateDeveloperDto;
use App\Domain\Developer\DeveloperEntity;
use App\Domain\Developer\DeveloperPageDto;
use App\Domain\Developer\GetDevelopersDto;
use App\Domain\Developer\IDeveloperRepository;
use App\Domain\Developer\UpdateDeveloperDto;
use App\Exceptions\WithErrorCodeException;
use App\Models\Developer;
use App\Persistence\Converters\DeveloperConverter;
use App\Persistence\Converters\DtoToModelConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeveloperRepository implements IDeveloperRepository
{
    public function create(CreateDeveloperDto $data): DeveloperEntity
    {
        $developer = Developer::create(DtoToModelConverter::toArray($data));
        return DeveloperConverter::toDomain($developer);
    }

    public function find(int $id): DeveloperEntity
    {
        $item = Developer::find($id);
        if ($item == null) {
            throw new ModelNotFoundException('Developer not found');
        }

        return DeveloperConverter::toDomain($item);
    }

    public function paginate(GetDevelopersDto $data): DeveloperPageDto
    {
        $query = Developer::query();

        if ($data->name) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($data->name) . '%']);
        }

        if ($data->sortRating) {
            $sortOrder = $data->sortRating === 'desc' ? 'desc' : 'asc';
            $query->orderBy('rating', $sortOrder);
        }

        $pagination = $query->paginate(perPage: $data->perPage, page: $data->page);
        return new DeveloperPageDto(
            $pagination->total(),
            $pagination->currentPage(),
            $pagination->perPage(),
            $pagination->items()
        );
    }

    public function update(UpdateDeveloperDto $data): DeveloperEntity
    {
        $developer = Developer::find($data->id);
        if ($developer == null) {
            throw new WithErrorCodeException('Developer not found', 404);
        }

        $developer->update(DtoToModelConverter::toArray($data));
        return DeveloperConverter::toDomain($developer);
    }

    public function delete(int $id): void
    {
        $developer = Developer::find($id);

        if ($developer == null) {
            throw new WithErrorCodeException('Developer not found', 404);
        }

        $developer->delete();
    }

}

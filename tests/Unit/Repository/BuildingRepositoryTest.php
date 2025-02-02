<?php

namespace Tests\Unit\Repository;

use App\Domain\Building\IBuildingRepository;
use App\Exceptions\WithErrorCodeException;
use App\Models\Building;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Repository\BuildingRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;
use Tests\Util\BuildingMother;
use Tests\Util\TestUtil;

class BuildingRepositoryTest extends TestCase
{
    private BuildingRepository $repository;
    private Building $buildingModelMock;
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildingModelMock = Mockery::mock(Building::class);
        $this->repository = new BuildingRepository($this->buildingModelMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateSuccess(): void
    {
        $createDto = BuildingMother::defaultCreateBuildingDto();
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->buildingModelMock
            ->shouldReceive('newQuery->create')
            ->once()
            ->andReturn(new Building(DtoToModelConverter::toArray($buildingEntity)));

        $result = $this->repository->create($createDto);

        $arr1 = (array)$buildingEntity;
        $arr2 = (array)$result;
        TestUtil::unsetKeys(['id'], $arr1, $arr2);
        $this->assertEquals($arr1, $arr2);
    }

    public function testFindSuccess(): void
    {
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->buildingModelMock
            ->shouldReceive('newQuery->find')
            ->once()
            ->with($buildingEntity->id)
            ->andReturn(new Building(DtoToModelConverter::toArray($buildingEntity)));

        $result = $this->repository->find($buildingEntity->id);

        $arr1 = (array)$buildingEntity;
        $arr2 = (array)$result;
        TestUtil::unsetKeys(['id'], $arr1, $arr2);
        $this->assertEquals($arr1, $arr2);
    }

    public function testFindThrowsExceptionWhenNotFound(): void
    {
        $this->buildingModelMock
            ->shouldReceive('newQuery->find')
            ->once()
            ->andReturn(null);

        $this->expectException(ModelNotFoundException::class);

        $this->repository->find(999);
    }

    public function testUpdateSuccess(): void
    {
        $updateDto = BuildingMother::defaultUpdateBuildingDto();
        $updateDto->address = 'new address';
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->buildingModelMock
            ->shouldReceive('newQuery->find')
            ->once()
            ->with($updateDto->id)
            ->andReturn(new Building(array_merge(
                DtoToModelConverter::toArray($buildingEntity),
                DtoToModelConverter::toArray($updateDto)
            )));

        $this->buildingModelMock
            ->shouldReceive('newQuery->where->update')
            ->once()
            ->with(Mockery::on(fn($arg) => $arg['id'] === $updateDto->id));

        $result = $this->repository->update($updateDto);

        $this->assertEquals($updateDto->address, $result->address);
    }

    public function testUpdateFailsWhenNotFound(): void
    {
        $updateDto = BuildingMother::defaultUpdateBuildingDto();

        $this->buildingModelMock
            ->shouldReceive('newQuery->find')
            ->once()
            ->with($updateDto->id)
            ->andReturn(null);

        $this->expectException(WithErrorCodeException::class);

        $this->repository->update($updateDto);
    }

    public function testDeleteSuccess(): void
    {
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->buildingModelMock
            ->shouldReceive('newQuery->find')
            ->once()
            ->with($buildingEntity->id)
            ->andReturn($this->buildingModelMock);

        $this->buildingModelMock
            ->shouldReceive('delete')
            ->once();

        $this->repository->delete($buildingEntity->id);

        $this->assertTrue(true);
    }

    public function testDeleteFailsWhenNotFound(): void
    {
        $this->buildingModelMock
            ->shouldReceive('newQuery->find')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->expectException(WithErrorCodeException::class);
        $this->expectExceptionMessage('Building not found');

        $this->repository->delete(999);
    }
}

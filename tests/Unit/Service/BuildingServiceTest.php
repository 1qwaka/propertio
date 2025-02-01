<?php

namespace Test\Unit\Service;

use App\Domain\Building\IBuildingRepository;
use App\Services\BuildingService;
use PHPUnit\Framework\TestCase;
use Mockery;
use Tests\Util\BuildingMother;

class BuildingServiceTest extends TestCase
{
    private IBuildingRepository $repository;
    private BuildingService $service;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(IBuildingRepository::class);
        $this->service = new BuildingService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetTypes(): void
    {
        $expectedTypes = ['Type1', 'Type2', 'Type3'];

        $this->repository
            ->shouldReceive('getTypes')
            ->once()
            ->andReturn($expectedTypes);

        $result = $this->service->getTypes();

        $this->assertEquals($expectedTypes, $result);
    }

    public function testCreate(): void
    {
        $data = BuildingMother::defaultCreateBuildingDto();
        $expectedEntity = BuildingMother::defaultBuildingEntity();

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($expectedEntity);

        $result = $this->service->create($data);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testFind(): void
    {
        $id = 1;
        $expectedEntity = BuildingMother::defaultBuildingEntity();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($expectedEntity);

        $result = $this->service->find($id);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testGet(): void
    {
        $page = 1;
        $perPage = 10;
        $expectedPageDto = BuildingMother::defaultBuildingPageDto();

        $this->repository
            ->shouldReceive('paginate')
            ->once()
            ->with($page, $perPage)
            ->andReturn($expectedPageDto);

        $result = $this->service->get($page, $perPage);

        $this->assertEquals($expectedPageDto, $result);
    }

    public function testUpdate(): void
    {
        $data = BuildingMother::defaultUpdateBuildingDto();
        $expectedEntity = BuildingMother::defaultBuildingEntity();

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($data)
            ->andReturn($expectedEntity);

        $result = $this->service->update($data);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testDelete(): void
    {
        $id = 1;

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($id);

        $this->service->delete($id);

        // Проверяем, что метод был вызван
        $this->assertTrue(true);
    }
}

<?php

namespace Tests\Unit\Controller;

use App\Domain\Building\CreateBuildingDto;
use App\Domain\Building\IBuildingService;
use App\Domain\Building\UpdateBuildingDto;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Illuminate\Http\Request;
use App\Http\Controllers\BuildingController;
use Tests\TestCase;
use Tests\Util\BuildingMother;
use Tests\Util\TestUtil;

class BuildingControllerTest extends TestCase
{
    private IBuildingService $service;
    private BuildingController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = Mockery::mock(IBuildingService::class);
        $this->controller = new BuildingController($this->service);
        Validator::shouldReceive('make->fails')->andReturn(false);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testTypes(): void
    {
        $expectedTypes = ['Type1', 'Type2', 'Type3'];

        $this->service
            ->shouldReceive('getTypes')
            ->once()
            ->andReturn($expectedTypes);

        $request = new Request();
        $response = $this->controller->types($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Success',
            'items' => $expectedTypes,
        ], $response->getData(true));
    }

    public function testCreateSuccess(): void
    {
        $requestData = BuildingMother::defaultCreateBuildingDto();

        $request = new Request(request: (array) $requestData);
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->service
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::type(CreateBuildingDto::class))
            ->andReturn($buildingEntity);

        Validator::shouldReceive('make->safe->all')->once()->andReturn((array)$requestData);

        $response = $this->controller->create($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Success',
            'item' => TestUtil::objToArray($buildingEntity),
        ], $response->getData(true));
    }

    public function testReadById(): void
    {
        $id = 1;
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->service
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($buildingEntity);

        $request = Request::create("/read/{$id}", 'GET');
        $response = $this->controller->readById($request, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Success',
            'item' => TestUtil::objToArray($buildingEntity),
        ], $response->getData(true));
    }

    public function testRead(): void
    {
        $page = 1;
        $perPage = 10;
        $buildingPageDto = BuildingMother::defaultBuildingPageDto();

        $this->service
            ->shouldReceive('get')
            ->once()
            ->with($page, $perPage)
            ->andReturn($buildingPageDto);

        $request = Request::create('/read', 'GET', ['page' => $page, 'perPage' => $perPage]);
        $response = $this->controller->read($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(TestUtil::objToArray($buildingPageDto), $response->getData(true));
    }

    public function testUpdateSuccess(): void
    {
        $id = 1;
        $requestData = BuildingMother::defaultUpdateBuildingDto();

        $request = new Request(request: (array) $requestData);
        $buildingEntity = BuildingMother::defaultBuildingEntity();

        $this->service
            ->shouldReceive('update')
            ->once()
            ->with(Mockery::type(UpdateBuildingDto::class))
            ->andReturn($buildingEntity);

        $dataArr = (array)$requestData;
        unset($dataArr['id']);
        Validator::shouldReceive('make->safe->all')->once()->andReturn($dataArr);

        $response = $this->controller->update($request, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Building updated successfully',
            'item' => TestUtil::objToArray($buildingEntity),
        ], $response->getData(true));
    }

    public function testDelete(): void
    {
        $id = 1;

        $this->service
            ->shouldReceive('delete')
            ->once()
            ->with($id);

        $request = Request::create("/delete/{$id}", 'DELETE');
        $response = $this->controller->delete($request, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Building deleted successfully',
        ], $response->getData(true));
    }
}

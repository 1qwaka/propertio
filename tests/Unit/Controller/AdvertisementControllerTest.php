<?php

namespace Tests\Unit\Controller;

use App\Domain\Advertisement\CreateAdvertisementDto;
use App\Domain\Advertisement\GetAdvertisementsDto;
use App\Domain\Advertisement\IAdvertisementService;
use App\Domain\Advertisement\UpdateAdvertisementDto;
use App\Http\Controllers\AdvertisementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;
use Tests\Util\AdvertisementMother;
use Tests\Util\TestUtil;

class AdvertisementControllerTest extends TestCase
{
    private IAdvertisementService $advertisementServiceMock;
    private AdvertisementController $advertisementController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->advertisementServiceMock = Mockery::mock(IAdvertisementService::class);
        $this->advertisementController = new AdvertisementController($this->advertisementServiceMock);

        Validator::shouldReceive('make->fails')->andReturn(false);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreate(): void
    {
        // Arrange
        $createAdvertisementDto = AdvertisementMother::createAdvertisementDto();
        $entity = AdvertisementMother::advertisementEntity();

        Validator::shouldReceive('make->safe->merge')->once()->andReturn((array)$createAdvertisementDto);
        Validator::shouldReceive('make->getValue')->once()->andReturn($createAdvertisementDto->type->value);

        $this->advertisementServiceMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::type(CreateAdvertisementDto::class))
            ->andReturn($entity);

        $request = new Request((array)$createAdvertisementDto);

        // Act
        $response = $this->advertisementController->create($request);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'Success',
            'item' => array_merge((array)$entity, ['type' => $entity->type->value]),
        ], json_decode($response->getContent(), true));
    }

    public function testReadById(): void
    {
        // Arrange
        $id = 1;
        $entity = AdvertisementMother::advertisementEntity();

        $this->advertisementServiceMock
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($entity);

        $request = new Request();

        // Act
        $response = $this->advertisementController->readById($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'Success',
            'item' => array_merge((array)$entity, ['type' => $entity->type->value]),
        ], json_decode($response->getContent(), true));
    }

    public function testRead(): void
    {
        // Arrange
        $getAdvertisementsDto = AdvertisementMother::getAdvertisementsDto();
        $entities = AdvertisementMother::advertisementPageDto();

        Validator::shouldReceive('make->safe->all')->once()->andReturn((array)$getAdvertisementsDto);

        $this->advertisementServiceMock
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::type(GetAdvertisementsDto::class))
            ->andReturn($entities);

        $request = new Request((array)$getAdvertisementsDto);

        // Act
        $response = $this->advertisementController->read($request);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            TestUtil::objToArray($entities),
            json_decode($response->getContent(), true)
        );
    }

    public function testUpdate(): void
    {
        // Arrange
        $id = 1;
        $updateAdvertisementDto = AdvertisementMother::updateAdvertisementDto();
        $entity = AdvertisementMother::advertisementEntity();

        Validator::shouldReceive('make->safe->merge')->once()->andReturn((array)$updateAdvertisementDto);
        Validator::shouldReceive('make->getValue')->once()->andReturn($updateAdvertisementDto->type->value);

        $this->advertisementServiceMock
            ->shouldReceive('update')
            ->once()
            ->with(Mockery::type(UpdateAdvertisementDto::class))
            ->andReturn($entity);

        $request = new Request((array)$updateAdvertisementDto);

        // Act
        $response = $this->advertisementController->update($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'Advertisement updated successfully',
            'item' => array_merge((array)$entity, ['type' => $entity->type->value]),
        ], json_decode($response->getContent(), true));
    }

    public function testDelete(): void
    {
        // Arrange
        $id = 1;

        $this->advertisementServiceMock
            ->shouldReceive('delete')
            ->once()
            ->with($id);

        $request = new Request();

        // Act
        $response = $this->advertisementController->delete($request, $id);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            ['message' => 'Advertisement deleted successfully'],
            json_decode($response->getContent(), true)
        );
    }
}

<?php

namespace Tests\Unit\Service;

use App\Domain\Advertisement\IAdvertisementRepository;
use App\Domain\Agent\IAgentService;
use App\Domain\Property\IPropertyService;
use App\Services\AdvertisementService;
use App\Exceptions\WithErrorCodeException;
use Mockery;
use Tests\TestCase;
use Tests\Util\AdvertisementMother;
use Tests\Util\AgentMother;
use Tests\Util\PropertyMother;

class AdvertisementServiceTest extends TestCase
{
    private IAdvertisementRepository $repositoryMock;
    private IAgentService $agentServiceMock;
    private IPropertyService $propertyServiceMock;
    private AdvertisementService $advertisementService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(IAdvertisementRepository::class);
        $this->agentServiceMock = Mockery::mock(IAgentService::class);
        $this->propertyServiceMock = Mockery::mock(IPropertyService::class);

        $this->advertisementService = new AdvertisementService(
            $this->repositoryMock,
            $this->agentServiceMock,
            $this->propertyServiceMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateSuccess(): void
    {
        $createDto = AdvertisementMother::createAdvertisementDto();
        $advertisement = AdvertisementMother::advertisementEntity();
        $agentEntity = AgentMother::regularAgentEntity();
        $property = PropertyMother::propertyEntityWithParams(agentId: $agentEntity->id);

        $this->propertyServiceMock
            ->shouldReceive('find')
            ->once()
            ->with($createDto->propertyId)
            ->andReturn($property);

        $this->agentServiceMock
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agentEntity);

        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(fn($arg) => $arg->agentId === $createDto->agentId))
            ->andReturn($advertisement);

        $result = $this->advertisementService->create($createDto);

        $this->assertSame($advertisement, $result);
    }

    public function testCreateFailsWhenAgentMismatch(): void
    {
        $createDto = AdvertisementMother::createAdvertisementDto();
        $agentEntity = AgentMother::regularAgentEntity();
        $property = PropertyMother::propertyEntityWithParams(agentId: $agentEntity->id + 1);

        $this->propertyServiceMock
            ->shouldReceive('find')
            ->once()
            ->with($createDto->propertyId)
            ->andReturn($property);

        $this->agentServiceMock
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agentEntity);

        $this->expectException(WithErrorCodeException::class);

        $this->advertisementService->create($createDto);
    }

    public function testFind(): void
    {
        $advertisement = AdvertisementMother::advertisementEntity();

        $this->repositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($advertisement->id)
            ->andReturn($advertisement);

        $result = $this->advertisementService->find($advertisement->id);

        $this->assertSame($advertisement, $result);
    }

    public function testUpdateSuccess(): void
    {
        $updateDto = AdvertisementMother::updateAdvertisementDto();
        $agentEntity = AgentMother::regularAgentEntity();
        $advertisement = AdvertisementMother::advertisementEntity();
        $advertisement->agentId = $agentEntity->id;

        $this->repositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($updateDto->id)
            ->andReturn($advertisement);

        $this->agentServiceMock
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agentEntity);

        $this->repositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($updateDto)
            ->andReturn($advertisement);


        $result = $this->advertisementService->update($updateDto);

        $this->assertSame($advertisement, $result);
    }

    public function testUpdateFailsWhenNoPermission(): void
    {
        $updateDto = AdvertisementMother::updateAdvertisementDto();
        $advertisement = AdvertisementMother::advertisementEntity();
        $agentEntity = AgentMother::regularAgentEntity();

        $this->repositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($updateDto->id)
            ->andReturn($advertisement);

        $this->agentServiceMock
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agentEntity);

        $this->expectException(WithErrorCodeException::class);

        $this->advertisementService->update($updateDto);
    }

    public function testDeleteSuccess(): void
    {
        $agentEntity = AgentMother::regularAgentEntity();
        $advertisement = AdvertisementMother::advertisementEntity();
        $advertisement->agentId = $agentEntity->id;

        $this->repositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($advertisement->id)
            ->andReturn($advertisement);

        $this->agentServiceMock
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agentEntity);

        $this->repositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($advertisement->id);

        $this->advertisementService->delete($advertisement->id);

        $this->assertTrue(true);
    }
}

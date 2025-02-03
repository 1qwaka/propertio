<?php

namespace Tests\Integration;

use App\Domain\Developer\GetDevelopersDto;
use App\Models\Developer;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Repository\DeveloperRepository;
use App\Services\DeveloperService;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\DeveloperMother;
use Tests\Util\TestUtil;

class DeveloperTest extends TestCase
{
    use DatabaseTruncation;

    private DeveloperService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new DeveloperRepository(new Developer);
        $this->service = new DeveloperService($repository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }
    }

    #[Epic('Integration')]
    public function testCreate(): void
    {
        $data = DeveloperMother::defaultCreateDeveloperDto();

        $result = $this->service->create($data);

        $this->assertDatabaseHas('developers', array_merge(
            DtoToModelConverter::toArray($data),
            ['id' => $result->id]
        ));
    }

    #[Epic('Integration')]
    public function testFind(): void
    {
        $item = Developer::factory()->create();

        $result = $this->service->find($item->id);

        $arr1 = $item->toArray();
        $arr2 = DtoToModelConverter::toArray($result);
        TestUtil::unsetKeys(['created_at', 'updated_at'], $arr1, $arr2);
        $this->assertEquals($arr1, $arr2);
    }

    #[Epic('Integration')]
    public function testGet(): void
    {
        $page = 1;
        $perPage = 5;
        $items = Developer::factory($perPage)->create();

        $result = $this->service->get(new GetDevelopersDto($page, $perPage));

        foreach ($result->items as $item) {
            $this->assertTrue($items->contains(fn($b) => $b->id == $item->id));
        }
    }

    #[Epic('Integration')]
    public function testUpdate(): void
    {
        $model = Developer::factory()->create();
        $data = DeveloperMother::defaultUpdateDeveloperDto();
        $data->id = $model->id;

        $result = $this->service->update($data);

        $this->assertEquals($data->id, $result->id);
        $this->assertDatabaseHas('developers', [
            'id' => $data->id,
            'address' => $data->address,
        ]);
    }

    #[Epic('Integration')]
    public function testDelete(): void
    {
        $building = Developer::factory()->create();
        $id = $building->id;

        $this->service->delete($id);

        $this->assertDatabaseMissing('buildings', [
            'id' => $id,
        ]);
    }
}

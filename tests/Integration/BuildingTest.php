<?php

namespace Tests\Integration;

use App\Models\Building;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Repository\BuildingRepository;
use App\Services\BuildingService;
use Database\Seeders\BuildingTypeSeeder;
use Database\Seeders\DeveloperSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\BuildingMother;
use Tests\Util\TestUtil;

class BuildingTest extends TestCase
{
    use DatabaseTruncation;

    private BuildingService $service;

    protected function setUp(): void
    {
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }
        parent::setUp();
        Allure::epic('Integration');

        $repository = new BuildingRepository(new Building);
        $this->service = new BuildingService($repository);
    }

    #[Epic('Integration')]
    public function testCreate(): void
    {
        $this->fail('Test failing');
        $this->seed([BuildingTypeSeeder::class, DeveloperSeeder::class]);
        $data = BuildingMother::defaultCreateBuildingDto();
        $data->developerId = 1;

        $result = $this->service->create($data);

        $this->assertDatabaseHas('buildings', DtoToModelConverter::toArray($result));
    }
    #[Epic('Integration')]
    public function testFind(): void
    {
        $this->seed([BuildingTypeSeeder::class, DeveloperSeeder::class]);
        $building = Building::factory()->create();

        $result = $this->service->find($building->id);

        $arr1 = $building->toArray();
        $arr2 = DtoToModelConverter::toArray($result);
        TestUtil::unsetKeys(['created_at', 'updated_at', 'build_year'], $arr1, $arr2);
        $this->assertEquals($arr1, $arr2);
    }

    #[Epic('Integration')]
    public function testGet(): void
    {
        $this->seed([BuildingTypeSeeder::class, DeveloperSeeder::class]);
        $page = 1;
        $perPage = 5;
        $buildings = Building::factory(5)->create();

        $result = $this->service->get($page, $perPage);

        foreach ($result->items as $item) {
            $this->assertTrue($buildings->contains(fn ($b) => $b->id == $item->id));
        }
    }

    #[Epic('Integration')]
    public function testUpdate(): void
    {
        $this->seed([BuildingTypeSeeder::class, DeveloperSeeder::class]);
        $building = Building::factory()->create();
        $data = BuildingMother::defaultUpdateBuildingDto();
        $data->id = $building->id;
        $data->developerId = $building->developer_id;
        $data->address = "new address";
        $data->typeId = $building->type_id;

        $result = $this->service->update($data);

        $this->assertEquals($data->id, $result->id);
        $this->assertDatabaseHas('buildings', [
            'id' => $data->id,
            'address' => $data->address,
        ]);
    }

    #[Epic('Integration')]
    public function testDelete(): void
    {
        $this->seed([BuildingTypeSeeder::class, DeveloperSeeder::class]);
        $building = Building::factory()->create();
        $id = $building->id;

        $this->service->delete($id);

        $this->assertDatabaseMissing('buildings', [
            'id' => $id,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Domain\Building\CreateBuildingDto;
use App\Domain\Building\IBuildingService;
use App\Domain\Building\UpdateBuildingDto;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    public function __construct(
        private IBuildingService $service,
    )
    {
    }

    public function types(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'items' => $this->service->getTypes(),
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'type' => 'required|string|max:255',  // Принимаем type в виде строки (например, "кирпичный")
            'hotWater' => 'boolean',
            'gas' => 'boolean',
            'elevators' => 'integer|min:0',
            'floors' => 'required|integer|min:1',
            'buildYear' => 'required|integer|min:1000',
            'developerId' => 'integer|exists:developers,id',
            'address' => 'required|string|max:100',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $data = new CreateBuildingDto(...$validated->safe()->all());
        $building = $this->service->create($data);

        return response()->json([
            'message' => 'Success',
            'item' => $building
        ]);
    }

    public function readById(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'item' => $this->service->find($id)
        ]);
    }

    public function read(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error',
                'errors' => $validated->errors()
            ], 400);
        }

        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('perPage', 10);

        $data = $this->service->get($page, $perPage);
        return response()->json($data);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'typeId' => 'integer|exists:building_type,id',
            'hotWater' => 'boolean',
            'gas' => 'boolean',
            'elevators' => 'integer|min:0',
            'floors' => 'integer|min:1',
            'buildYear' => 'integer|min:1000',
            'developerId' => 'integer|exists:developers,id',
            'address' => 'string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $data = new UpdateBuildingDto($id, ...$validated->safe()->all());
        $building = $this->service->update($data);

        return response()->json([
            'message' => 'Building updated successfully',
            'item' => $building
        ]);

    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Building deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Domain\Property\CreatePropertyDto;
use App\Domain\Property\GetPropertiesDto;
use App\Domain\Property\IPropertyService;
use App\Domain\Property\LivingSpaceType;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    public function __construct(
        private IPropertyService $propertyService,
    )
    {
    }

    public function types(Request $request)
    {
        return response()->json([
            'message' => 'Success',
            'items' => $this->propertyService->getFloorTypes(),
        ]);
    }

    public function spaceTypes(Request $request)
    {
        return response()->json([
            'message' => 'Success',
            'items' => $this->propertyService->getSpaceTypes(),
        ]);
    }

    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'renovation' => 'string|max:255',
            'buildingId' => 'integer|min:0|exists:buildings,id',
            'floor' => 'required|integer',
            'area' => 'integer|min:1',
            'floorTypeId' => 'required|integer|exists:floor_type,id',
            'address' => 'required|string|max:100',
            'livingSpaceType' => 'required|string|in:primary,secondary',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error',
                'errors' => $validated->errors()
            ], 400);
        }

        $data = new CreatePropertyDto(...$validated->safe()->merge([
            'livingSpaceType' => LivingSpaceType::from($validated->getValue('livingSpaceType')),
        ]));

        return response()->json([
            'message' => 'Success',
            'item' => $this->propertyService->create($data),
        ]);
    }

    // Метод для получения записи помещения по ID
    public function readById(Request $request, int|string $id)
    {
        return response()->json([
            'message' => 'Success',
            'item' => $this->propertyService->find($id),
        ]);
    }

    // Метод для получения списка помещений с пагинацией
    public function read(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1',
            'agentId' => 'integer|exists:agents,id',
            'livingSpaceType' => 'string|in:primary,secondary',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error',
                'errors' => $validated->errors()
            ], 400);
        }

        $data = new GetPropertiesDto(...$validated->safe()->merge([
            'livingSpaceType' => LivingSpaceType::tryFrom($validated->getValue('livingSpaceType')),
        ]));

        $page = $this->propertyService->get($data);
        return response()->json($page);
    }

    // Метод для частичного обновления записи помещения
    public function update(Request $request, int $id)
    {
        $validated = Validator::make($request->all(), [
            'renovation' => 'string|max:255',
            'floor' => 'integer',
            'area' => 'integer|min:1',
            'floorTypeId' => 'integer|exists:floor_type,id',
            'address' => 'string|max:100',
            'livingSpaceType' => 'string|in:primary,secondary',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $validated = $validated->safe();

        // Найдем помещение по его ID
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        $agent = Auth::user()->agent;
        if ($agent->id != $property->agent_id) {
            return response()->json(['message' => 'You don\'t have access to edit this property'], 403);
        }

        // Обновляем запись
        try {
            $property->update($validated->all());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Success', 'item' => $property]);
    }

    // Метод для удаления записи помещения
    public function delete(Request $request, $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        $agent = Auth::user()->agent;
        if ($agent->id != $property->agent_id) {
            return response()->json(['message' => 'You don\'t have access to delete this property'], 403);
        }

        try {
            $property->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Property deleted successfully']);
    }

    public function self(Request $request)
    {
        $property = Property::where('agent_id', Auth::user()->agent->id)->get();

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return response()->json(['message' => 'Success', 'item' => $property]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    public function types(Request $request)
    {
        $types = DB::table('building_type')->get();

        return response()->json([ 'message' => 'Success', 'items' => $types]);
    }

    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'type' => 'required|string|max:255',  // Принимаем type в виде строки (например, "кирпичный")
            'hot_water' => 'boolean',
            'gas' => 'boolean',
            'elevators' => 'integer|min:0',
            'floors' => 'required|integer|min:1',
            'build_year' => 'required|integer|min:1000',
            'district_id' => 'integer|exists:districts,id',
            'developer_id' => 'integer|exists:developers,id',
            'address' => 'required|string|max:100',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $validated = $validated->safe();

        // Найдем type_id по имени типа здания
        $type = Building::findTypeByName($validated['type']);

        if ($type == null) {
            return response()->json(['message' => 'Building Type is not valid'], 400);
        }

        // Создание нового объекта Building
        $building = Building::create([
            'type_id' => $type->id,  // Вставляем найденный type_id
            'hot_water' => $validated['hot_water'] ?? null,
            'gas' => $validated['gas'] ?? null,
            'elevators' => $validated['elevators'] ?? null,
            'floors' => $validated['floors'],
            'build_year' => $validated['build_year'],
            'district_id' => $validated['district_id'],
            'developer_id' => $validated['developer_id'],
            'address' => $validated['address'],
        ]);

        return response()->json(['message' => 'Success', 'item' => $building]);
    }

    public function readById(Request $request, int $id)
    {
        // Поиск здания по ID
        $item = Building::find($id);

        if ($item == null) {
            return response()->json(['message' => 'Building not found'], 404);
        }

        return response()->json(compact('item'));
    }

    public function read(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        $query = Building::query();

        // Пагинация
        $items = $query->paginate($perPage);

        return response()->json([
            'total' => $items->total(),
            'current' => $items->currentPage(),
            'perPage' => $items->perPage(),
            'items' => $items->items(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = Validator::make($request->only([
            'type', 'hot_water', 'gas', 'elevators', 'floors', 'build_year', 'district_id', 'developer_id', 'address'
        ]), [
            'type' => 'string|max:255',  // Принимаем type в виде строки, если передан
            'hot_water' => 'boolean',
            'gas' => 'boolean',
            'elevators' => 'integer|min:0',
            'floors' => 'integer|min:1',
            'build_year' => 'integer|min:1000',
            'district_id' => 'integer|exists:districts,id',
            'developer_id' => 'integer|exists:developers,id',
            'address' => 'string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $validated = $validated->safe();

        // Найдем здание по его ID
        $building = Building::find($id);

        if ($building == null) {
            return response()->json(['message' => 'Building not found'], 404);
        }

        $update_values = $validated->all();

        // Проверка и поиск type_id, если был передан параметр type
        if (isset($validated['type'])) {
            $type = Building::findTypeByName($validated['type']);
            if ($type == null) {
                return response()->json(['message' => 'Building Type is not valid'], 400);
            }
            $update_values['type_id'] = $type->id;
        }

        // Обновляем только те поля, которые были переданы
        $building->update($update_values);

        return response()->json([
            'message' => 'Building updated successfully',
            'item' => $building
        ]);
    }

    public function delete(Request $request, int $id)
    {
        // Поиск здания по ID
        $building = Building::find($id);

        if ($building == null) {
            return response()->json(['message' => 'Building not found'], 404);
        }

        try {
            $building->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to delete building',
            ], 409);
        }

        return response()->json([
            'message' => 'Building deleted successfully',
        ]);
    }
}

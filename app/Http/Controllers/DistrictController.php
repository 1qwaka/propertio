<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'city_id' => 'required|integer|min:0',
            'population' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $city = City::find($validated['city_id']);

        if ($city == null) {
            return response()->json(['message' => 'city_id is invalid'], 400);
        }

        $district = District::create($validated->only(['city_id', 'population', 'area', 'name', 'rating']));

        return response()->json(['message' => 'Success', 'district' => $district]);
    }


    public function readById(Request $request, int $id)
    {
        $item = District::find($id);

        if ($item == null) {
            return response()->json(['message' => 'District not found'], 404);
        }

        return response()->json(compact('item'));
    }

    public function read(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        // Фильтрация по имени города (если параметр name передан в запросе)
        $query = District::query();

        if ($request->has('name')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->get('name')) . '%']);
        }

        // Сортировка по рейтингу (если параметр sortRating передан в запросе)
        if ($request->has('sortRating')) {
            $sortOrder = $request->get('sortRating') === 'desc' ? 'desc' : 'asc';
            $query->orderBy('rating', $sortOrder);
        }

        // Пагинация
        $items = $query->paginate($perPage);

        return response()->json([
            'total' => $items->total(),
            'current' => $items->currentPage(),
            'perPage' => $items->perPage(),
            'items' => $items->items(),
        ]);
    }


    public function update(Request $request,  int $id)
    {
        $validated = Validator::make($request->only(['city_id', 'name', 'population', 'area', 'rating']), [
            'city_id' => 'integer|min:0',
            'name' => 'string|max:255',
            'population' => 'integer|min:0',
            'area' => 'numeric|min:0',
            'rating' => 'numeric|min:0|max:5',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $item = District::find($id);

        if ($item == null) {
            return response()->json(['message' => 'District not found'], 404);
        }

        if ($request->has('city_id')) {
            $city = City::find($validated['city_id']);

            if ($city == null) {
                return response()->json(['message' => 'city_id is invalid'], 400);
            }
        }

        // Обновим город с помощью метода update, который только обновит переданные поля
        $item->update($validated->all());

        // Вернем обновленный объект в ответе
        return response()->json([
            'message' => 'District updated successfully',
            'item' => $item,
        ]);
    }

    public function delete(Request $request,  int $id)
    {
        $item = District::find($id);

        if ($item == null) {
            return response()->json(['message' => 'District not found']);
        }

        $item->delete();

        return response()->json([
            'message' => 'District deleted successfully',
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'population' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $city = City::create($validated->only(['population', 'area', 'name', 'rating']));

        return response()->json(['message' => 'Success', 'item' => $city]);
    }

    public function readById(Request $request, int $id)
    {
        $item = City::find($id);

        if ($item == null) {
            return response()->json(['message' => 'City not found'], 404);
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
        $query = City::query();

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
        $validated = Validator::make($request->only(['name', 'population', 'area', 'rating']), [
            'name' => 'string|max:255',
            'population' => 'integer|min:0',
            'area' => 'numeric|min:0',
            'rating' => 'numeric|min:0|max:5', // Предполагаем, что рейтинг от 0 до 5
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        // Найдем город по его ID
        $city = City::find($id);

        if ($city == null) {
            return response()->json(['message' => 'City not found'], 400);
        }

        // Обновим город с помощью метода update, который только обновит переданные поля
        $city->update($validated->all());

        // Вернем обновленный объект в ответе
        return response()->json([
            'message' => 'City updated successfully',
            'item' => $city
        ]);
    }

    public function delete(Request $request,  int $id)
    {
        $city = City::find($id);

        if ($city == null) {
            return response()->json(['message' => 'City not found'], 400);
        }

        $city->delete();

        return response()->json([
            'message' => 'City deleted successfully',
        ]);
    }
}

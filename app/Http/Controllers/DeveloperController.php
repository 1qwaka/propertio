<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
            'email' => 'required|email|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $developer = Developer::create($validated->only(['address', 'name', 'rating', 'email']));

        return response()->json(['message' => 'Success', 'item' => $developer]);
    }

    public function readById(Request $request, int $id)
    {
        $item = Developer::find($id);

        if ($item == null) {
            return response()->json(['message' => 'Developer not found'], 404);
        }

        return response()->json(compact('item'));
    }

    public function read(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        // Фильтрация по имени разработчика (если параметр name передан в запросе)
        $query = Developer::query();

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
        $validated = Validator::make($request->only(['address', 'name', 'rating', 'email']), [
            'address' => 'string|max:255',
            'name' => 'string|max:255',
            'rating' => 'numeric|min:0|max:5', // Рейтинг от 0 до 5
            'email' => 'email|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        // Найдем разработчика по его ID
        $developer = Developer::find($id);

        if ($developer == null) {
            return response()->json(['message' => 'Developer not found'], 400);
        }

        // Обновим разработчика с помощью метода update, который только обновит переданные поля
        $developer->update($validated->all());

        // Вернем обновленный объект в ответе
        return response()->json([
            'message' => 'Developer updated successfully',
            'item' => $developer
        ]);
    }

    public function delete(Request $request,  int $id)
    {
        $developer = Developer::find($id);

        if ($developer == null) {
            return response()->json(['message' => 'Developer not found'], 400);
        }

        $developer->delete();

        return response()->json([
            'message' => 'Developer deleted successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    // Метод для создания объявления
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'description' => 'string|max:255',
            'price' => 'required|numeric|min:0',
            'property_id' => 'required|integer|exists:properties,id',
            'type' => 'required|string|in:sell,rent',
            'hidden' => 'boolean',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $property = Property::find($validated['property_id']);
        if ($property->agent_id != Auth::user()->agent->id) {
            return response()->json(['message' => 'You don\'t have access to create advertisements with this property'], 403);
        }

        try {
            $advertisement = Advertisement::create([
                'agent_id' => Auth::user()->agent->id,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'property_id' => $validated['property_id'],
                'type' => $validated['type'],
                'hidden' => $validated['hidden'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Success', 'item' => $advertisement], 201);
    }

    public function readById(Request $request, int $id)
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        return response()->json(['message' => 'Success', 'item' => $advertisement], 200);
    }

    public function read(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        $query = Advertisement::query();

        $query->where('hidden', false);

        if ($request->has('agent_id')) {
            $query->where('agent_id', $request->get('agent_id'));
        }

        $advertisements = $query->paginate($perPage);

        return response()->json([
            'total' => $advertisements->total(),
            'current' => $advertisements->currentPage(),
            'perPage' => $advertisements->perPage(),
            'items' => $advertisements->items(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = Validator::make($request->only(['description', 'price', 'type', 'hidden']), [
            'description' => 'string|max:255',
            'price' => 'numeric|min:0',
            'type' => 'string|in:sell,rent',
            'hidden' => 'boolean',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $validated = $validated->safe();

        // Найти объявление по ID
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        // Проверить, что текущий агент является автором объявления
        if ($advertisement->agent_id != Auth::user()->agent->id) {
            return response()->json(['message' => 'You don\'t have access to edit this advertisement'], 403);
        }

        // Обновить объявление
        try {
            $advertisement->update($validated->all());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Advertisement updated successfully', 'item' => $advertisement]);
    }

    public function delete(Request $request, int $id)
    {
        $advertisement = Advertisement::find($id);

        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        if ($advertisement->agent_id != Auth::user()->agent->id) {
            return response()->json(['message' => 'You don\'t have access to delete this advertisement'], 403);
        }

        try {
            $advertisement->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'Advertisement deleted successfully']);
    }


    public function self(Request $request)
    {
        $advertisement = Advertisement::where('agent_id', Auth::user()->agent->id)->get();

        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        return response()->json(['message' => 'Success', 'items' => $advertisement]);
    }
}

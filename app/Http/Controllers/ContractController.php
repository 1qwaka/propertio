<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    // Метод для агента для создания сделки
    public function create(Request $request)
    {
        // Валидация входных данных
        $validated = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'date' => 'string|max:255',
            'price' => 'required|numeric|min:0',
            'buyer_id' => 'required|exists:users,id',
            'until' => 'string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        if (Property::find($validated['property_id'])->agent_id != Auth::user()->agent->id) {
            return response()->json(['message' => 'No access to this property'], 403);
        }

        if ($validated['buyer_id'] == Auth::id()) {
            return response()->json(['message' => 'Can not create contract with yourself'], 400);
        }

        try {
            $create_array = [
                'property_id' => $validated['property_id'],
                'status' => 'open',
                'price' => $validated['price'],
                'buyer_id' => $validated['buyer_id'],
                'agent_id' => Auth::user()->agent->id, // агент, создавший сделку
                'until' => $validated['until'] ?? null,
            ];
            if ($request->has('date')) {
                $create_array['date'] = $validated['date'];
            }
            // Создание сделки агентом
            $contract = Contract::create($create_array);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 400);
        }

        return response()->json(['message' => 'Success', 'item' => $contract], 201);
    }

    // Метод для редактирования сделки агентом (только если статус 'open')
    public function update(Request $request, int $id)
    {
        $validated = Validator::make($request->only(['date', 'price', 'until']), [
//            'property_id' => 'sometimes|exists:properties,id',
            'date' => 'string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'until' => 'nullable|date',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $contract = Contract::where('id', $id)
            ->where('agent_id', Auth::user()->agent->id)
            ->where('status', 'open')
            ->first();

        if ($contract == null) {
            return response()->json(['message' => 'Contract not found or cannot be updated'], 409);
        }

        $contract->update($validated->all());

        return response()->json(['message' => 'Contract updated successfully', 'item' => $contract]);
    }

    // Метод для удаления сделки агентом (только если статус 'open')
    public function delete(Request $request, int $id)
    {
        $contract = Contract::where('id', $id)
            ->where('agent_id', Auth::user()->agent->id)
            ->where('status', 'open')
            ->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found or cannot be deleted'], 400);
        }

        $contract->delete();

        return response()->json(['message' => 'Contract deleted successfully']);
    }

    // Метод для принятия или отклонения сделки пользователем
    public function changeStatus(Request $request,  $id)
    {
//        echo $id;
//        return response()->json(['id' => $id]);

        $validated = Validator::make($request->all(), [
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

        $contract = Contract::where('id', $id)
            ->where('buyer_id', Auth::user()->id)
            ->where('status', 'open')
            ->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found or cannot be changed'], 400);
        }

        $contract->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Contract status updated successfully', 'item' => $contract]);
    }

    // Метод для чтения всех сделок агента
    public function readAgent(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $contracts = Contract::where('agent_id', Auth::user()->agent->id)
            ->paginate($perPage);

        return response()->json([
            'total' => $contracts->total(),
            'current' => $contracts->currentPage(),
            'perPage' => $contracts->perPage(),
            'items' => $contracts->items(),
        ]);
    }

    // Метод для чтения всех сделок пользователя
    public function readUser(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $contracts = Contract::where('buyer_id', Auth::user()->id)
            ->paginate($perPage);

        return response()->json([
            'total' => $contracts->total(),
            'current' => $contracts->currentPage(),
            'perPage' => $contracts->perPage(),
            'contracts' => $contracts->items(),
        ]);
    }

    // Метод для чтения сделки по ID (доступно агенту и пользователю)
    public function readById(Request $request, int $id)
    {
        $contract = Contract::where('id', $id)
            ->where(function ($query) {
                $query->where('agent_id', Auth::user()->agent->id)
                    ->orWhere('buyer_id', Auth::user()->id);
            })
            ->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        return response()->json(['contract' => $contract]);
    }
}

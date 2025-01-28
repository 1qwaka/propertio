<?php

namespace App\Http\Controllers;

use App\Models\ViewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ViewRequestController extends Controller
{
    // Создание заявки на просмотр
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
//            'date' => 'required|date|after_or_equal:today',
            'date' => 'required|string|max:255',
            'property_id' => 'required|integer|exists:properties,id',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validated->errors()], 400);
        }

        $validated = $validated->safe();

        // Создаем заявку с привязкой к пользователю, который делает запрос
        $viewRequest = ViewRequest::create([
            'status' => 'open',
            'date' => $validated['date'],
            'property_id' => $validated['property_id'],
            'user_id' => Auth::id(), // текущий авторизованный пользователь
        ]);

        return response()->json(['message' => 'View request created successfully', 'item' => $viewRequest]);
    }

    public function readAgent(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        // Получаем текущего пользователя (агента или обычного пользователя)
        $user = Auth::user();

        // Если агент, то выводим заявки на просмотр для всех его помещений
//        $viewRequests = ViewRequest::whereHas('property', function ($query) use ($user) {
//            $query->where('agent_id', $user->agent->id);
//        });
        $viewRequests = ViewRequest::join('properties', 'view_requests.property_id', '=', 'properties.id')
            ->where('properties.agent_id', $user->agent->id)
            ->select('view_requests.*'); // Если нужно выбрать только поля из view_requests

        // Пагинация
        $viewRequests = $viewRequests->paginate($perPage);

        return response()->json([
            'total' => $viewRequests->total(),
            'current' => $viewRequests->currentPage(),
            'perPage' => $viewRequests->perPage(),
            'items' => $viewRequests->items(),
        ]);
    }

    public function readUser(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        if ($perPage <= 0) {
            $perPage = 10;
        }

        // Получаем текущего пользователя (агента или обычного пользователя)
        $user = Auth::user();

        $viewRequests = ViewRequest::where('user_id', $user->id);

        // Пагинация
        $viewRequests = $viewRequests->paginate($perPage);

        return response()->json([
            'total' => $viewRequests->total(),
            'current' => $viewRequests->currentPage(),
            'perPage' => $viewRequests->perPage(),
            'items' => $viewRequests->items(),
        ]);
    }

    // Получение заявки по ID (доступно агенту или автору заявки)
    public function readById(Request $request, $id)
    {
        $viewRequest = ViewRequest::find($id);
        if (!$viewRequest) {
            return response()->json(['message' => 'View request not found'], 404);
        }

        $user = Auth::user();

        // Проверяем, что агент или пользователь имеют право на просмотр
        if ($user->agent && $viewRequest->property->agent_id == $user->agent->id || $viewRequest->user_id == $user->id) {
            return response()->json(['item' => $viewRequest]);
        }

        return response()->json(['message' => 'Access denied'], 403);
    }

    public function update(Request $request, $id)
    {
        $viewRequest = ViewRequest::find($id);
        if (!$viewRequest) {
            return response()->json(['message' => 'View request not found'], 404);
        }

        if (Auth::id() != $viewRequest->user_id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        if ($viewRequest->status == 'accepted' || $viewRequest->status == 'rejected') {
            return response()->json(['message' => 'Cannot edit accepted/rejected request'], 400);
        }

        $validated = Validator::make($request->only(['date']), [
//            'date' => 'date|after_or_equal:today',
            'date' => 'string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validated->errors()], 400);
        }

        $validated = $validated->safe();

        $viewRequest->update($validated->all());

        return response()->json(['message' => 'View request updated successfully', 'item' => $viewRequest]);
    }

    public function delete(Request $request, int $id)
    {
        $viewRequest = ViewRequest::find($id);
        if (!$viewRequest) {
            return response()->json(['message' => 'View request not found'], 404);
        }

        if (Auth::id() != $viewRequest->user_id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $viewRequest->delete();

        return response()->json(['message' => 'View request deleted successfully']);
    }

    // Метод для агента, чтобы принять или отклонить заявку
    public function changeStatus(Request $request, int $id)
    {
//        echo "dasdas";
        $validated = Validator::make($request->all(), [
            'status' => 'required|string|in:accepted,rejected',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validated->errors()], 400);
        }
        $validated = $validated->safe();

//        почему вот эта конструкция при id = 5001 возвращает мне
        $viewRequest = ViewRequest::find($id);
        if (!$viewRequest) {
            return response()->json(['message' => 'View request not found'], 404);
        }

        $agent = Auth::user()->agent;
        if (!$agent || $viewRequest->property->agent_id != $agent->id) {
            return response()->json([
                'message' => 'Access denied',
//                'agent' => $agent,
//                '$viewRequest->property' => $viewRequest->property,
//                '$viewRequest' =>$viewRequest,
//                'id' => $id
            ], 403);
        }

        // Обновляем статус
        $viewRequest->status = $validated['status'];
        $viewRequest->save();

        return response()->json(['message' => 'View request status updated successfully', 'item' => $viewRequest]);
    }
}


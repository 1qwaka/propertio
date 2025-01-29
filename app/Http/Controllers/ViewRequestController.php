<?php

namespace App\Http\Controllers;

use App\Domain\Agent\IAgentService;
use App\Domain\User\IUserService;
use App\Domain\ViewRequest\CreateViewRequestDto;
use App\Domain\ViewRequest\GetViewRequestDto;
use App\Domain\ViewRequest\IViewRequestService;
use App\Domain\ViewRequest\ViewRequestStatus;
use App\Models\ViewRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ViewRequestController extends Controller
{
    public function __construct(
        private readonly IViewRequestService $service,
    )
    {
    }

    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
//            'date' => 'required|date|after_or_equal:today',
            'date' => 'required|string|max:255',
            'propertyId' => 'required|integer|exists:properties,id',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validated->errors()], 400);
        }

        $data = new CreateViewRequestDto(...$validated->safe()->all());
        return response()->json([
            'message' => 'View request created successfully',
            'item' => $this->service->create($data),
        ]);
    }

    public function readAgent(Request $request)
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

        $data = new GetViewRequestDto(...$validated->safe()->all());
        return response()->json($this->service->getAgent($data));
    }

    public function readUser(Request $request)
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

        $data = new GetViewRequestDto(...$validated->safe()->all());
        return response()->json($this->service->getUser($data));
    }

    // Получение заявки по ID (доступно агенту или автору заявки)
    public function readById(Request $request, int $id)
    {
        return response()->json([
            'message' => 'Success',
            'item' => $this->service->find($id),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = Validator::make($request->all(), [
//            'date' => 'date|after_or_equal:today',
            'date' => 'string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validated->errors()], 400);
        }

        return response()->json([
            'message' => 'View request updated successfully',
            'item' => $this->service->updateDate($id, Carbon::parse($validated->getValue('date'))),
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'View request deleted successfully']);
    }

    // Метод для агента, чтобы принять или отклонить заявку
    public function changeStatus(Request $request, int $id)
    {
        $validated = Validator::make($request->all(), [
            'status' => 'string|in:accepted,rejected',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validated->errors()], 400);
        }

        $status = ViewRequestStatus::from($validated->getValue('status'));
        return response()->json([
            'message' => 'View request status updated successfully',
            'item' => $this->service->updateStatus($id, $status),
        ]);
    }
}


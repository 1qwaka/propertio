<?php

namespace App\Http\Controllers;

use App\Domain\Advertisement\AdvertisementStatus;
use App\Domain\Advertisement\CreateAdvertisementDto;
use App\Domain\Advertisement\GetAdvertisementsDto;
use App\Domain\Advertisement\IAdvertisementService;
use App\Domain\Advertisement\UpdateAdvertisementDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    public function __construct(
        private IAdvertisementService $advertisementService,
    )
    {
    }

    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'description' => 'string|max:255',
            'price' => 'required|integer|min:0',
            'propertyId' => 'required|integer|exists:properties,id',
            'type' => 'required|string|in:sell,rent',
            'hidden' => 'boolean',
        ]);

        if ($validated->fails()) {
            return response()->json(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $data = new CreateAdvertisementDto(...$validated->safe()->merge([
            'type' => AdvertisementStatus::from($validated->getValue('type')),
        ]));

        return response()->json([
            'message' => 'Success',
            'item' => $this->advertisementService->create($data),
        ]);
    }

    public function readById(Request $request, int $id)
    {
        return response()->json([
            'message' => 'Success',
            'item' => $this->advertisementService->find($id),
        ]);
    }

    public function read(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1',
            'agentId' => 'integer|exists:agents,id',
            'hidden' => 'boolean',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error',
                'errors' => $validated->errors()
            ], 400);
        }

        $data = new GetAdvertisementsDto(...$validated->safe()->all());

        return response()->json($this->advertisementService->get($data));
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

        $data = new UpdateAdvertisementDto(...$validated->safe()->merge([
            'type' => AdvertisementStatus::tryFrom($validated->getValue('type')),
            'id' => $id,
        ]));
        return response()->json([
            'message' => 'Advertisement updated successfully',
            'item' => $this->advertisementService->update($data),
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $this->advertisementService->delete($id);
        return response()->json(['message' => 'Advertisement deleted successfully']);
    }

}

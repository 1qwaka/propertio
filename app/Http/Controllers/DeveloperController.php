<?php

namespace App\Http\Controllers;

use App\Domain\Developer\CreateDeveloperDto;
use App\Domain\Developer\GetDevelopersDto;
use App\Domain\Developer\IDeveloperService;
use App\Domain\Developer\UpdateDeveloperDto;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    public function __construct(
        private readonly IDeveloperService $service,
    )
    {
    }

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

        $data = new CreateDeveloperDto(...$validated->safe()->all());
        return response()->json([
            'message' => 'Success',
            'item' => $this->service->create($data),
            'validated' =>$validated->safe()->all(),
        ]);
    }

    public function readById(Request $request, int $id)
    {
        return response()->json([
            'message' => 'Success',
            'item' => $this->service->find($id),
        ]);
    }

    public function read(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1',
            'name' => 'string',
            'sortRating' => 'string|in:asc,desc',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error',
                'errors' => $validated->errors()
            ], 400);
        }

        $data = new GetDevelopersDto(...$validated->safe()->all());
        return response()->json($this->service->get($data));
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

        $data = new UpdateDeveloperDto(...$validated->safe()->all());
        return response()->json([
            'message' => 'Developer updated successfully',
            'item' => $this->service->update($data),
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $this->service->delete($id);
        return response()->json([
            'message' => 'Developer deleted successfully',
        ]);
    }
}

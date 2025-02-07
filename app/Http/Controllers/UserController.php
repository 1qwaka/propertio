<?php

namespace App\Http\Controllers;

use App\Domain\User\CreateUserDto;
use App\Domain\User\IUserService;
use App\Domain\User\LoginUserDto;
use App\Exceptions\WithErrorCodeException;
use App\Http\Requests\UserRequest;
use App\Http\View\UserView;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(
        private readonly IUserService $userService,
    )
    {
    }

    public function self(Request $request)
    {
        $self = $this->userService->getSelf();
        return response()->json([
            'message' => 'success',
            'item' => UserView::toArray($self),
        ]);
    }

    public function register(UserRequest $request): JsonResponse
    {
//        if ($validated->fails()) {
//            return response()->json(['message' => 'Registration failed', 'errors' => $validated->errors()], 401);
//        }
        $validated = $request->validated();

        $data = new CreateUserDto(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
        );

        $user = $this->userService->create($data);

        return response()->json([
            'message' => 'Registration successful',
            'user' => UserView::toArray($user)
        ], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($credentials->fails()) {
            return response()->json(['message' => 'Login failed', 'errors' => $credentials->errors()], 401);
        }

        $data = new LoginUserDto(...$credentials->safe()->only(['email', 'password']));

        try {
            $user = $this->userService->login($data);
            return response()->json(['message' => 'Login successful', 'user' => UserView::toArray($user)]);
        } catch (WithErrorCodeException $e) {
            return response()->json(['message' => 'Login failed'], 401);
        }
    }

    public function login2(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($credentials->fails()) {
            return response()->json(['message' => 'Login failed', 'errors' => $credentials->errors()], 401);
        }


        $data = new LoginUserDto(...$credentials->safe()->only(['email', 'password']));

        try {
            $this->userService->login2($data);
            return response()->json(['message' => 'Credentials valid, check your email for auth code']);
        } catch (WithErrorCodeException $e) {
            return response()->json(['message' => 'Login failed'], 401);
        }
    }
    public function confirm(Request $request): JsonResponse
    {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'code' => 'required|string',
        ]);

        if ($credentials->fails()) {
            return response()->json(['message' => 'Confirm failed', 'errors' => $credentials->errors()], 401);
        }

        $data = new LoginUserDto(...$credentials->safe()->only(['email', 'password']));

        try {
            $user = $this->userService->confirm(
                $data,
                $credentials->getValue('code')
            );
            return response()->json(['message' => 'Login successful', 'user' => UserView::toArray($user)]);
        } catch (WithErrorCodeException $e) {
            return response()->json(['message' => 'Login failed'], 401);
        }
    }



    public function logout(Request $request): JsonResponse
    {
        try {
            $this->userService->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['message' => 'Logout success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout error: ' . $e->getMessage()]);
        }
    }
}


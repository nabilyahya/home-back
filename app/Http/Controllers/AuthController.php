<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        Log::info('Register method called', ['request' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:worker,customer',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        Log::info('User successfully registered', ['user' => $user]);
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => 'User successfully logged out']);
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    public function getAuthenticatedUser(Request $request)
    {
        return response()->json(auth()->user());
    }
}

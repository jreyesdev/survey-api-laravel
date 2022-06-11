<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a user
     * @param RegisterRequest $req
     * @return Response
     */
    public function register(RegisterRequest $req): Response
    {
        /** @var User $user */
        $user = User::create($req->validated());
        // $user = $req->validated();

        $token = $user->createToken('main-token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login user
     * @param
     */
    public function login(LoginRequest $req): Response
    {
        $credentials = $req->validated();

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'errors' => [
                    'email' => ['The provided credentials are not correct']
                ]
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request): Response
    {
        /** @var User $user */
        $user = Auth::user();

        // $request->user()->currentAccessToken()->delete();
        $user->tokens()->delete();

        return response([
            'success' => true
        ]);
    }
}

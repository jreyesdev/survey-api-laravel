<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
}

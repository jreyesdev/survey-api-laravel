<?php

namespace Tests;

use App\Models\User;

class AuthUser
{
    /** Crea usuario y devuelve token
     * @return array
     */
    public static function createAuthUser(): array
    {
        $userAuth = User::factory()->create([
            'password' => 'password'
        ]);
        $token = $userAuth->createToken('token')->plainTextToken;
        return [$userAuth, $token];
    }
}

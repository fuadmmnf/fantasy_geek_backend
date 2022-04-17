<?php

namespace App\Workers;

use App\Models\User;

class UserTokenHandler
{
    public function createUser(array $userInfo): User
    {
        return User::create($userInfo);
    }

    public function regenerateUserToken(User $user){
        $user->tokens()->delete();
        $user->token = $user->createToken($user->mobile. $user->id)->plainTextToken;
        return $user;
    }

    public function revokeTokens(User $user){
        $user->tokens()->delete();
    }
}

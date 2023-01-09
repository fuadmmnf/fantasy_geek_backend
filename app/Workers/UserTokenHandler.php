<?php

namespace App\Workers;

use App\Models\User;

class UserTokenHandler
{
    public function createUser(array $userInfo): User
    {
        $user = new User();
        $user->name = $userInfo['name'];
        $user->email = $userInfo['email'];
        $user->mobile = $userInfo['mobile'];
        $user->address = $userInfo['address'];
        $user->password = $userInfo['password'];
        $user->save();
        return $user;
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

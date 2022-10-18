<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegistrationRequest;
use App\Http\Requests\User\VerifyRegistrationRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function authorizeUserLogin(LoginRequest $request)
    {
        $user = $this->userRepository->login($request->validated());

        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
    }

    public function createUser(RegistrationRequest $request)
    {
        $user = $this->userRepository->createAccount($request->validated());

        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
    }
    public function verifyUser(VerifyRegistrationRequest $request)
    {
        $user = $this->userRepository->verifyAccount($request->validated());

        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
    }

    public function updateUser(UpdateUserRequest $request) {
        $user = $this->userRepository->updateAccount($request->validated());

        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
    }

    public function checkUser($id) {
        $user = $this->userRepository->checkUserAccount($id);

        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
    }
}

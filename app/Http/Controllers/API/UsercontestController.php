<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usercontest\UsercontestByFixtureResource;
use App\Repositories\UsercontestRepository;
use Illuminate\Http\Request;

class UsercontestController extends Controller
{
    public function __construct(UsercontestRepository $usercontestRepository)
    {
        $this->usercontestRepository = $usercontestRepository;
    }

    public function getUsercontestsByFixture($user_id, $fixture_id) {
        $usercontests = $this->usercontestRepository->getConstestsByFixture($user_id, $fixture_id);

        return UsercontestByFixtureResource::collection($usercontests);
    }
}

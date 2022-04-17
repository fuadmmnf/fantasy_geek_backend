<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Userotp;
use App\Workers\UserTokenHandler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Return_;

class UserRepository
{

    public function login(array $request)
    {
        if(!isset($request['otp'])) {
            $otp = $this->generateOTP($request['mobile']);
            return 'OTP created successfully. Your OTP is ' . $otp;
        }
        else {
            $user = User::where('mobile', $request['mobile'])->firstOrFail();
            $userotp = Userotp::where('mobile', $request['mobile'])->firstOrFail();
            if($userotp->otp == $request['otp']) {
                if($user && Hash::check($request['password'], $user->password)){
                    $userTokenHandler = new UserTokenHandler();
                    $user = $userTokenHandler->regenerateUserToken($user);
                    $user->load('roles');
                    return $user;
                }
            }  else {
                throw new \Exception('Invalid OTP');
            }

        }
        return null;
    }

    public function createAccount(array $request) {
        $newUser = new User();
        DB::beginTransaction();
        try {
            $newUser->mobile = $request['mobile'];
            $newUser->password = Hash::make($request['password']);
            $newUser->save();
            $newUser->assignRole('general');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        DB::commit();
        $otp = $this->generateOTP($request['mobile']);
        return 'OTP created successfully. Your OTP is ' . $otp;
    }

    public function verifyAccount(array $request) {
        $user = User::where('mobile', $request['mobile'])->firstOrFail();
        $userotp = Userotp::where('mobile', $request['mobile'])->firstOrFail();
        DB::beginTransaction();
        try {
            if($userotp->otp == $request['otp']) {
                $user->is_verified = 1;
                $user->save();
                $this->deleteOTP($request['mobile']);
                $userTokenHandler = new UserTokenHandler();
                $user = $userTokenHandler->regenerateUserToken($user);
                $user->load('roles');
            }
            else {
                throw new \Exception('Invalid OTP');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        DB::commit();
        return $user;
    }

    private function generateOTP($mobile)
    {
        $pool = '0123456789';
        $otp = substr(str_shuffle(str_repeat($pool, 4)), 0, 4);
        Userotp::where('mobile', $mobile)->delete();

        $newOTP = new Userotp();
        $newOTP->mobile = $mobile;
        $newOTP->otp = $otp;
        $newOTP->save();
        return $otp;
    }
    private function deleteOTP($mobile)
    {
        Userotp::where('mobile', $mobile)->delete();
    }
}

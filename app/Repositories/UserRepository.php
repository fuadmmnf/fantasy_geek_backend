<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Userotp;
use App\Workers\UserTokenHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

    public function login(array $request)
    {
        if(!isset($request['otp'])) {
            $otp = $this->generateOTP($request['mobile']);
            // ekhane SMS gateway bose...
            return 'OTP created successfully. Your OTP is ' . $otp;
        }
        else {
            $user = User::where('mobile', $request['mobile'])->first();
            $userotp = Userotp::where('mobile', $request['mobile'])->firstOrFail();
            if($userotp->otp == $request['otp']) {
                if ($user) {
                    $user->is_verified = 1;
                    $user->save();
                    $this->deleteOTP($request['mobile']);
                    $userTokenHandler = new UserTokenHandler();
                    $user = $userTokenHandler->regenerateUserToken($user);
                    $user->load('roles');
                    return [
                        'success' => true,
                        'user' => $user,
                        'message' => 'লগইন সফল হয়েছে!',
                    ];
                    // if($user && Hash::check($request['password'], $user->password)){
                    //     $userTokenHandler = new UserTokenHandler();
                    //     $user = $userTokenHandler->regenerateUserToken($user);
                    //     $user->load('roles');
                    //     return $user;
                    // }
                } else {
                    $newUser = new User();
                    DB::beginTransaction();
                    try {
                        $newUser->mobile = $request['mobile'];
                        $newUser->password = Hash::make('secret123');
                        $newUser->save();
                        $newUser->assignRole('general');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        // throw new \Exception($e->getMessage());
                        return [
                            'success' => false,
                            'message' => 'দুঃখিত! আবার চেষ্টা করুন।',
                        ];
                    }
                    DB::commit();
                    $user = User::where('mobile', $request['mobile'])->first();
                    $user->is_verified = 1;
                    $user->save();
                    $this->deleteOTP($request['mobile']);
                    $userTokenHandler = new UserTokenHandler();
                    $user = $userTokenHandler->regenerateUserToken($user);
                    $user->load('roles');
                    return [
                        'success' => true,
                        'user' => $user,
                        'message' => 'রেজিস্ট্রেশন সফল হয়েছে!',
                    ];
                }
            }  else {
                return [
                    'success' => false,
                    'message' => 'Invalid OTP',
                ];
                // throw new \Exception('Invalid OTP');
            }

        }
        return null;
    }

    public function createAccount(array $request) {
        $newUser = new User();
        DB::beginTransaction();
        try {
            $newUser->mobile = $request['mobile'];
            $newUser->password = Hash::make($request['password'] ? $request['password'] : 'secret123');
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

    public function updateAccount(array $request) {
        $user = User::where('mobile', $request['mobile'])->firstOrFail();
        DB::beginTransaction();
        try {
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->bkash = $request['bkash'];
            // ARO INFO ADD KORA LAGBE...
            // ARO INFO ADD KORA LAGBE...
            // ARO INFO ADD KORA LAGBE...
            $user->save();
            $userTokenHandler = new UserTokenHandler();
            $user = $userTokenHandler->regenerateUserToken($user);
            $user->load('roles');
        } catch (\Exception $e) {
            DB::rollBack();
            // throw new \Exception($e->getMessage());
            return [
                'success' => false,
                'message' => 'দুঃখিত! আপডেট করা সম্ভব হয়নি।',
            ];
        }
        DB::commit();
        return [
            'success' => true,
            'user' => $user,
            'message' => 'সফলভাবে আপডেট করা হয়েছে!',
        ];
    }

    public function checkUserAccount($id) {
        $user = User::findOrFail($id);
        return [
            'success' => true,
            'user' => $user,
            'message' => 'লেনদেন সফল হয়েছে ও কয়েন সংখ্যা বৃদ্ধি করা হয়েছে!',
        ];
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

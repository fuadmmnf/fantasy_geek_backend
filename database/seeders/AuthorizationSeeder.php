<?php

namespace Database\Seeders;

use App\Models\Expensecategory;
use App\Models\Store;
use App\Models\User;
use App\Workers\UserTokenHandler;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionInfos = [
            [
                'name' => 'user-crud',
                'display_name' => 'User CRUD',
                'description' => 'See only Listing Of Role'
            ],

            [
                'name' => 'admin-menu',
                'display_name' => 'Admin Menu',
                'description' => 'Admin Menu Permission'
            ],
            [
                'name' => 'match-crud',
                'display_name' => 'match CRUD',
                'description' => 'match CRUD Permission'
            ],
            [
                'name' => 'contest',
                'display_name' => 'Contest',
                'description' => 'Contest'
            ],

        ];

        $permissions = [];
        foreach ($permissionInfos as $key => $value) {
            $permissions[$value['name']] = Permission::create($value);
        }


        $roleInfos = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Admin',
                'description' => 'Super Admin'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Admin'
            ],
            [
                'name' => 'general',
                'display_name' => 'General',
                'description' => 'General'
            ],
        ];
        $roles = [];
        foreach ($roleInfos as $key => $value) {
            $roles[$value['name']] = Role::create($value);
        }

        $roles['superadmin']->syncPermissions($permissions);
        $roles['admin']->syncPermissions($permissions);
        $roles['general']->syncPermissions(array_slice($permissions, 4));

        $userInfos = [
            [
                'name' => 'Abdullah-Al-Jahid',
                'email' => 'jahid@fg11.com',
                'mobile' => '00000000000',
                'address' => 'Tongi, Gazipur',
                'password' => Hash::make('secret123')
            ]
        ];


        $userTokenHandler = new UserTokenHandler();
        $user = $userTokenHandler->createUser($userInfos[0]);
        $user->assignRole($roles['superadmin']);


    }
}

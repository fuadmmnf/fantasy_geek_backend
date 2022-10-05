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
                'name' => 'fixture-crud',
                'display_name' => 'fixture CRUD',
                'description' => 'fixture CRUD Permission'
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
            ],
            [
                'name' => '1 2efef ',
                'email' => 'jahqwid@fg11.com',
                'mobile' => '00000000001',
                'address' => 'Ton12gi, Gazipu1fr',
                'password' => Hash::make('secret123')
            ],
            [
                'name' => '12f-21f',
                'email' => 'jah12ff1id@fg11.com',
                'mobile' => '00000000002',
                'address' => 'To1fngi, Gaz1f2fipur',
                'password' => Hash::make('secret123')
            ],
            [
                'name' => 'qwiuhq iu hq',
                'email' => '12f12@fg11.com',
                'mobile' => '00000000003',
                'address' => 'T12 ongi, Gazipur',
                'password' => Hash::make('secret123')
            ],
            [
                'name' => 'Abdu1 oi',
                'email' => 'jahid12 @fg121.com',
                'mobile' => '00000000004',
                'address' => 'Tong i,1r 1 Gaz12ipur',
                'password' => Hash::make('secret123')
            ],
            [
                'name' => 'f1oifn1o id',
                'email' => 'jah12 id@fg1211.com',
                'mobile' => '00000000005',
                'address' => 'Tongi, Gazipur',
                'password' => Hash::make('secret123')
            ],
            [
                'name' => 'Abdul121Al-Jahid',
                'email' => 'ja122f1hid@fg11.com',
                'mobile' => '00000000006',
                'address' => 'Tongi, Ga1 1zipur',
                'password' => Hash::make('secret123')
            ],
            [
                'name' => 'Abd-Jahid',
                'email' => 'jahid11@fg11.com',
                'mobile' => '00000000007',
                'address' => 'To1 1ngi, Gazipur',
                'password' => Hash::make('secret123')
            ]
        ];


        $userTokenHandler = new UserTokenHandler();
        $user = $userTokenHandler->createUser($userInfos[0]);
        $user->assignRole($roles['superadmin']);

        foreach (range(1, count($userInfos)-1) as $i){
            $user = $userTokenHandler->createUser($userInfos[$i]);
            $user->assignRole($roles['general']);
        }
    }
}

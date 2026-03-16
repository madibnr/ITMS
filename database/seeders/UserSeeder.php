<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('slug', 'admin')->first();
        $manager = Role::where('slug', 'manager')->first();
        $itStaff = Role::where('slug', 'it-staff')->first();
        $userRole = Role::where('slug', 'user')->first();

        // Admin user
        $adminUser = User::create([
            'name' => 'System Admin',
            'email' => 'admin@itms.test',
            'password' => Hash::make('password'),
            'phone' => '08123456001',
            'department' => 'IT',
            'is_active' => true,
        ]);
        $adminUser->roles()->attach($admin);

        // Manager user
        $managerUser = User::create([
            'name' => 'IT Manager',
            'email' => 'manager@itms.test',
            'password' => Hash::make('password'),
            'phone' => '08123456002',
            'department' => 'IT',
            'is_active' => true,
        ]);
        $managerUser->roles()->attach($manager);

        // IT Staff users
        foreach ([
        ['name' => 'Budi Santoso', 'email' => 'budi@itms.test', 'department' => 'IT Support'],
        ['name' => 'Sari Dewi', 'email' => 'sari@itms.test', 'department' => 'IT Infrastructure'],
        ['name' => 'Andi Pratama', 'email' => 'andi@itms.test', 'department' => 'IT Support'],
        ] as $data) {
            $staff = User::create([
                ...$data,
                'password' => Hash::make('password'),
                'phone' => '0812345' . rand(1000, 9999),
                'is_active' => true,
            ]);
            $staff->roles()->attach($itStaff);
        }

        // Regular users
        foreach ([
        ['name' => 'Ahmad Faisal', 'email' => 'ahmad@itms.test', 'department' => 'Finance'],
        ['name' => 'Rina Marlina', 'email' => 'rina@itms.test', 'department' => 'HR'],
        ['name' => 'Dian Purnama', 'email' => 'dian@itms.test', 'department' => 'Marketing'],
        ['name' => 'Hendri Kusuma', 'email' => 'hendri@itms.test', 'department' => 'Operations'],
        ['name' => 'Lina Susanti', 'email' => 'lina@itms.test', 'department' => 'Finance'],
        ] as $data) {
            $u = User::create([
                ...$data,
                'password' => Hash::make('password'),
                'phone' => '0812345' . rand(1000, 9999),
                'is_active' => true,
            ]);
            $u->roles()->attach($userRole);
        }
    }
}

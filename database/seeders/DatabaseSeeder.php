<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\JobType;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Roles (Urutan ID penting: 1=Boss, 2=Admin, 3=Crew, 4=Editor)
        $roles = ['boss', 'admin', 'crew', 'editor'];
        foreach ($roles as $r) {
            Role::create(['name' => $r]);
        }

        // 2. Buat Akun BOSS
        User::create([
            'name' => 'Big Boss',
            'email' => 'boss@web.com',
            'phone_number' => '0811111111',
            'password' => Hash::make('password'),
            'role_id' => 1, // Boss
            'payday' => 'monthly',
            'income' => 0
        ]);

        // 3. Buat Akun ADMIN
        User::create([
            'name' => 'Mbak Admin',
            'email' => 'admin@web.com',
            'phone_number' => '0822222222',
            'password' => Hash::make('password'),
            'role_id' => 2, // Admin
            'payday' => 'monthly',
            'income' => 0
        ]);

        // 4. Buat Akun CREW
        User::create([
            'name' => 'Mas Crew',
            'email' => 'crew@web.com',
            'phone_number' => '0833333333',
            'password' => Hash::make('password'),
            'role_id' => 3, // Crew
            'payday' => 'weekly',
            'income' => 0
        ]);

        // 5. Buat Akun EDITOR
        User::create([
            'name' => 'Kang Edit',
            'email' => 'editor@web.com',
            'phone_number' => '0844444444',
            'password' => Hash::make('password'),
            'role_id' => 4, // Editor
            'payday' => 'weekly',
            'income' => 0
        ]);

        // 6. Buat Job Types
        JobType::create(['job_type_name' => 'Photo']);
        JobType::create(['job_type_name' => 'Video']);
        JobType::create(['job_type_name' => 'Live Streaming']);
    }
}
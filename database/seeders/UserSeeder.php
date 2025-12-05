<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'ADMIN',
            'ADMIN_EDITOR',
            'ATTIRE',
            'CLIENT',
            'DIREKTUR',
            'EDITOR',
            'PHOTOGRAFER',
            'MAKE_UP',
            'VIDEOGRAFER'
        ];

        foreach ($roles as $role) {
            User::create([
                'name' => ucfirst(strtolower($role)) . ' User',
                'email' => strtolower($role) . '@gmail.com',
                'password' => Hash::make('password'), // default
                'role' => $role,
            ]);
        }
    }
}

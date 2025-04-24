<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Kawan',
            'email' => 'kawanhrs@gmail.com',
            'password' => Hash::make('admin'),
            'role_id' => 3, // Admin
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'Reporter', 'level' => 1]);
        Role::create(['name' => 'Autorizador', 'level' => 2]);
        Role::create(['name' => 'Administrador', 'level' => 3]);
    }
}
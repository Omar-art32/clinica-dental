<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //roles iniciales
        Role::create(['name' => 'SUPER ADMINISTRADOR']);
        Role::create(['name' => 'ADMINISTRADOR']);
        Role::create(['name' => 'RECEPCIONISTA']);
        Role::create(['name' => 'PACIENTE']);
        Role::create(['name' => 'DOCTOR']);
    }
}

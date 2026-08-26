<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@avisos.test'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'rol' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'usuario@avisos.test'],
            [
                'name' => 'Usuario',
                'password' => Hash::make('password'),
                'rol' => 'lector',
                'email_verified_at' => now(),
            ],
        );

        User::where('email', 'jfelipe0502@gmail.com')->update([
            'name' => 'Jesus Felipe Ronquillo Garcia',
            'rol' => 'admin',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Usuarios de practica del blog. Con estos entras al sistema en clase.
 *
 *   admin@blog.test       / secreto123   (rol admin)
 *   editor@blog.test      / secreto123   (rol editor)
 *   jfelipe0502@gmail.com / password     (rol admin)
 *
 * Es idempotente: puedes correrlo las veces que quieras.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $emailsPermitidos = [
            'admin@blog.test',
            'editor@blog.test',
            'jfelipe0502@gmail.com',
        ];

        User::whereNotIn('email', $emailsPermitidos)->delete();

        $admin = User::updateOrCreate(
            ['email' => 'admin@blog.test'],
            [
                'name' => 'Admin del blog',
                'password' => Hash::make('secreto123'),
                'rol' => 'admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'editor@blog.test'],
            [
                'name' => 'Editor de guardia',
                'password' => Hash::make('secreto123'),
                'rol' => 'editor',
            ],
        );

        User::updateOrCreate(
            ['email' => 'jfelipe0502@gmail.com'],
            [
                'name' => 'Jesus Felipe Ronquillo Garcia',
                'password' => Hash::make('password'),
                'rol' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        if (Schema::hasTable('posts') && Schema::hasColumn('posts', 'user_id') && class_exists(\App\Models\Post::class)) {
            \App\Models\Post::whereNull('user_id')->update(['user_id' => $admin->id]);
        }
    }
}

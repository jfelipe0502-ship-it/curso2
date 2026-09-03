<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Usuarios de practica del blog. Con estos entras al sistema en clase.
 *
 *   admin@blog.test   / secreto123   (rol admin)
 *   editor@blog.test  / secreto123   (rol editor)
 *
 * Es idempotente: puedes correrlo las veces que quieras.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@blog.test']);
        $admin->name = 'Admin del blog';
        $admin->password = Hash::make('secreto123');
        if (Schema::hasColumn('users', 'rol')) {
            $admin->rol = 'admin';
        }
        $admin->save();

        $editor = User::firstOrNew(['email' => 'editor@blog.test']);
        $editor->name = 'Editor de guardia';
        $editor->password = Hash::make('secreto123');
        if (Schema::hasColumn('users', 'rol')) {
            $editor->rol = 'editor';
        }
        $editor->save();

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

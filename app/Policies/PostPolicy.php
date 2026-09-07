<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->rol === 'admin') {
            return true;
        }

        return null;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol, ['admin', 'editor']);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function deleteAny(User $user): bool
    {
        return false; // nadie borra en masa; el admin pasa por before()
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}

<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    public function test_editors_cannot_delete_any_posts_in_bulk(): void
    {
        $editor = new User;
        $editor->rol = 'editor';

        $this->actingAs($editor);

        $this->assertFalse(Gate::allows('deleteAny', Post::class));
    }

    public function test_admins_can_delete_any_posts_in_bulk(): void
    {
        $admin = new User;
        $admin->rol = 'admin';

        $this->actingAs($admin);

        $this->assertTrue(Gate::allows('deleteAny', Post::class));
    }

    public function test_missing_abilities_are_denied_even_for_admins(): void
    {
        $admin = new User;
        $admin->rol = 'admin';

        $this->actingAs($admin);

        $this->assertFalse(Gate::allows('missingAbility', Post::class));
    }
}
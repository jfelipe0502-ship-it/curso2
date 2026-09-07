<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The public blog can be visited without authentication.
     */
    public function test_the_public_blog_is_available_without_authentication(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}

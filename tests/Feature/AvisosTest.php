<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sin token responde 401', function () {
    $this->postJson('/api/avisos', ['titulo' => 'x'])
        ->assertStatus(401);
});

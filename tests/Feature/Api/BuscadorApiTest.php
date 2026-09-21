<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('el buscador filtra los avisos por titulo', function () {
    Post::factory()->create(['titulo' => 'Cambio de horario en barandilla']);
    Post::factory()->create(['titulo' => 'Curso de primeros auxilios']);

    $this->getJson('/api/avisos?q=horario')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.titulo', 'Cambio de horario en barandilla');
});

test('sin texto devuelve todos los publicados', function () {
    Post::factory()->count(2)->create();

    $this->getJson('/api/avisos')
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(4),
            'contenido' => fake()->paragraph(),
            'resumen' => fake()->sentence(),
            'categoria_id' => Categoria::factory(),
            'user_id' => User::factory(),
            'publicado' => true,
        ];
    }

    public function borrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'publicado' => false,
        ]);
    }
}

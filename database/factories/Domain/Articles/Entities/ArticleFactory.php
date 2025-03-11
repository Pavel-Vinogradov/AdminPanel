<?php

declare(strict_types=1);

namespace Database\Factories\Domain\Articles\Entities;

use App\Domain\Articles\Entities\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(20),
            'content' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories\Domain\Comments\Entities;

use App\Domain\Articles\Entities\Article;
use App\Domain\Comments\Entities\Comment;
use App\Domain\Users\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'body' => $this->faker->word(),
            'parent_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'article_id' => Article::factory(),
            'user_id' => User::factory(),
        ];
    }
}

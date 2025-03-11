<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Articles\Entities\Article;
// use App\Domain\Users\Entities\User;
use App\Domain\Comments\Entities\Comment;
use App\Domain\Statistic\Entities\ViewStatistic;
use App\Domain\Users\Entities\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        Article::factory(30)->create();
        // Comment::factory()->create();
//        ViewStatistic::factory()->count(10)->create();
    }
}

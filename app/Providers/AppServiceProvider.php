<?php

namespace App\Providers;

use App\Domain\Articles\Services\ArticleService;
use App\Domain\Articles\Services\ArticleServiceInterface;
use App\Domain\Comments\Services\CommentService;
use App\Domain\Comments\Services\CommentServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
    }
}

<?php

declare(strict_types=1);

use App\Domain\Articles\Controllers\ArticleController;
use App\Domain\Statistic\Controller\StatisticController;
use App\Domain\Users\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('welcome');
});

Route::get('/dashboard', static fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function (): void {
    Route::resource('articles', ArticleController::class);
    Route::resource('statistic', StatisticController::class);
    Route::resource('users', UserController::class);

});
// Публичные маршруты
Route::get('/articles', [ArticleController::class, 'publicIndex'])->name('articles.public.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

require __DIR__ . '/auth.php';

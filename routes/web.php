<?php

declare(strict_types=1);

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => view('welcome'));

Route::get('/dashboard', static fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function (): void {
    Route::resource('articles', ArticleController::class);
});

// Публичные маршруты
Route::get('/articles', [ArticleController::class, 'publicIndex'])->name('articles.public.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

Route::middleware(['xml', 'auth', 'verified']);

require __DIR__ . '/auth.php';

<?php

declare(strict_types=1);

use App\Domain\Comments\Controllers\CommentController;
use App\Domain\Users\Controllers\UserController;

Route::post('/comments', [CommentController::class, 'store'])->name('api.comments.store');
Route::post('/soap/user', [UserController::class, 'soap']);

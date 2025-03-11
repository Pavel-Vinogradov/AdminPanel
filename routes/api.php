<?php

declare(strict_types=1);

use App\Domain\Comments\Controllers\CommentController;

Route::post('/comments', [CommentController::class, 'store'])->name('api.comments.store');

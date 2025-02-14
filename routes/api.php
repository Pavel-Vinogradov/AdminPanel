<?php

declare(strict_types=1);

use App\Http\Controllers\CommentController;

Route::post('/comments', [CommentController::class, 'store'])->name('api.comments.store');

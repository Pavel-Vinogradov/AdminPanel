<?php

use App\Http\Controllers\CommentController;

Route::post('/comments', [CommentController::class, 'store'])->name('api.comments.store');

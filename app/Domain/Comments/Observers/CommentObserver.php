<?php

declare(strict_types=1);

namespace App\Domain\Comments\Observers;

use App\Domain\Comments\Entities\Comment;
use App\Domain\Comments\Notifications\NewCommentNotification;
use App\Domain\Users\Entities\User;

final class CommentObserver
{
    public function created(Comment $comment): void
    {
        if ($comment->parent_id) {
            $parent = Comment::find($comment->parent_id);
            if ($parent && $parent->user_id) {
                $user = User::find($parent->user_id);
                $user?->notify(new NewCommentNotification($comment));
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Events;

use App\Domain\Comments\Entities\Comment;
use App\Domain\Comments\Resources\CommentResource;
use App\Domain\Users\Repositories\UserRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class CommentAddedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Comment $comment)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('article.' . $this->comment->article_id),
        ];
    }

    public function broadcastWith(): array
    {

        if (!$this->comment->user && $this->comment->user_id) {
            $user = $this->getUserRepository()->findById((int)$this->comment->user_id);
            $this->comment->user = $user;
        }

        return [
            'comment' => new CommentResource($this->comment),
        ];
    }

    public function getUserRepository()
    {
        return app(UserRepository::class);
    }
}

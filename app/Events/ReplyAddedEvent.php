<?php

declare(strict_types=1);

namespace App\Events;

use App\Domain\Comments\Entities\Comment;
use App\Domain\Comments\Resources\CommentResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ReplyAddedEvent implements ShouldBroadcast
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
        return [
            'comment' => new CommentResource($this->comment),
        ];
    }
}

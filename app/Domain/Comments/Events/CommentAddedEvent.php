<?php

namespace App\Domain\Comments\Events;

use App\Domain\Comments\Entities\Comment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class CommentAddedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Comment $comment) {}

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('articles.'.$this->comment->article_id),
        ];
    }
}

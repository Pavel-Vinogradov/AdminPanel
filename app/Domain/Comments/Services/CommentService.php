<?php

declare(strict_types=1);

namespace App\Domain\Comments\Services;

use App\Domain\Comments\DTOs\CommentDTO;
use App\Domain\Comments\Entities\Comment;
use App\Domain\Comments\Notifications\NewCommentNotification;
use App\Domain\Comments\Repositories\CommentRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\Events\CommentAddedEvent;
use App\Events\ReplyAddedEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class CommentService implements CommentServiceInterface
{
    public function __construct(
        public CommentRepository $commentRepository,
        public UserRepository $userRepository
    ) {}

    public function create(CommentDTO $commentDTO): Comment
    {
        $comment = $this->commentRepository->create($commentDTO->toArray());
        if (! $comment) {
            throw new BadRequestHttpException();
        }
        if ($comment->user_id) {
            $comment->user = $this->userRepository->findById((int) $comment->user_id);
        }
        if ($comment->parent_id) {
            broadcast(new ReplyAddedEvent($comment));
            $parentComment = $this->commentRepository->findById((int) $comment->parent_id);
            if ($parentComment && $parentComment->user_id !== $commentDTO->user_id) {
                if ($parentComment->user_id) {
                    $parentComment->user = $this->userRepository->findById((int) $comment->user_id);
                }
                if ($parentComment->user) {
                    Notification::send($parentComment->user, new NewCommentNotification($comment));
                }
            }
        }

        broadcast(new CommentAddedEvent($comment));

        return $comment;
    }

    public function getCommentsForArticle(int $articleId): Collection
    {
        $comments = $this->commentRepository->getCommentsForArticle($articleId);
        foreach ($comments as $comment) {
            $comment->user = $this->userRepository->findById((int) $comment->user_id);
        }

        return $comments;
    }
}

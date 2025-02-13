<?php

declare(strict_types=1);

namespace App\Domain\Comments\Services;

use App\Domain\Comments\DTOs\CommentDTO;
use App\Domain\Comments\Entities\Comment;
use App\Domain\Comments\Events\CommentAddedEvent;
use App\Domain\Comments\Notifications\NewCommentNotification;
use App\Domain\Comments\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class CommentService implements CommentServiceInterface
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
        if ($comment->parent_id) {
            $parentComment = $this->commentRepository->findById($comment->parent_id);
            if ($parentComment && $parentComment->user_id !== Auth::id()) {
                Notification::send($parentComment->user, new NewCommentNotification($comment));
            }
        }

        // Обновление комментариев в реальном времени
        broadcast(new CommentAddedEvent($comment))->toOthers();

        return $comment;
    }

    public function getCommentsForArticle(int $articleId): Collection
    {
        $comments = $this->commentRepository->getCommentsForArticle($articleId);
        foreach ($comments as $comment) {
            $comment->user = $this->userRepository->findById($comment->user_id);
        }

        return $comments;
    }
}

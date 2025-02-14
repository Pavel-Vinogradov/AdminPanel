<?php

declare(strict_types=1);

namespace App\Domain\Comments\Services;

use App\Domain\Comments\DTOs\CommentDTO;
use App\Domain\Comments\Entities\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentServiceInterface
{
    public function getCommentsForArticle(int $articleId): Collection;

    public function create(CommentDTO $commentDTO): Comment;
}

<?php

declare(strict_types=1);

namespace App\Domain\Comments\DTOs;

use Tizix\DataTransferObject\DataTransferObject;

final class CommentDTO extends DataTransferObject
{
    public string $body;

    public ?int $article_id;

    public ?int $parent_id;

    public ?int $user_id;
}

<?php

declare(strict_types=1);

namespace App\Domain\Comments\DTOs;

use Tizix\DataTransferObject\DataTransferObject;

final class CommentDTO extends DataTransferObject
{
    public string $body;

    public ?string $article_id;

    public ?string $parent_id;

    public ?string $user_id;
}

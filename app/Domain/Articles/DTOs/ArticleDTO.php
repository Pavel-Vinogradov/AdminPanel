<?php

declare(strict_types=1);

namespace App\Domain\Articles\DTOs;

use Tizix\DataTransferObject\DataTransferObject;

class ArticleDTO extends DataTransferObject
{
    public string $title;

    public string $content;

    public ?string $image = null;
}

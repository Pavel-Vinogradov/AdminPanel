<?php

declare(strict_types=1);

namespace App\Domain\Articles\Entities;

use Illuminate\Database\Eloquent\Builder;

final class ArticleBuilder extends Builder
{
    public function byId(int $id): ArticleBuilder
    {
        return $this->where('id', $id);
    }
}

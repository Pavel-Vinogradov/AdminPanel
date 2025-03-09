<?php

declare(strict_types=1);

namespace App\Domain\Comments\Repositories;

use App\Core\Repositories\PaginateRepositoryInterface;
use App\Domain\Comments\Entities\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class CommentRepository implements PaginateRepositoryInterface
{
    public function __construct(private Comment $model)
    {
    }

    public function paginate(int $perPage = 20, int $currentPage = 1, ?string $sortBy = null, string $sortOrder = 'asc'): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($sortBy) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate(perPage: $perPage, pageName: $currentPage);
    }

    /**
     * @return Collection<int,Comment>
     */
    public function getCommentsForArticle(int $articleId): Collection
    {
        return $this->model
            ->newQuery()
            ->where('article_id', (string)$articleId)
            ->with(['replies'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function findById(int $parent_id): ?Comment
    {
        return $this->model->newQuery()->find((string)$parent_id);
    }

    public function create(array $attributes): ?Comment
    {
        return $this->model->newQuery()->create($attributes);
    }
}

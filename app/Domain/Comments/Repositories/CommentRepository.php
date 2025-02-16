<?php

declare(strict_types=1);

namespace App\Domain\Comments\Repositories;

use App\Core\Repositories\PaginateRepositoryInterface;
use App\Domain\Comments\Entities\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class CommentRepository implements PaginateRepositoryInterface
{
    public function __construct(private Comment $model) {}

    public function paginate(int $perPage = 20, int $currentPage = 1, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->paginate($perPage, $columns, 'page', $currentPage);
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
        return $this->model->newQuery()->findOrFail($parent_id);
    }

    public function create(array $attributes): ?Comment
    {
        return $this->model->newQuery()->create($attributes);
    }
}

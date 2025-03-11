<?php

declare(strict_types=1);

namespace App\Domain\Articles\Repositories;

use App\Core\Repositories\BaseRepository;
use App\Core\Repositories\PaginateRepositoryInterface;
use App\Domain\Articles\Entities\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @method Article|null findById(int $modelId)
 * @method Article create(array $attributes)
 * @method Article|null update(int $modelId, array $attributes)
 * @method Collection <int, Article> getAll(array $columns = ['*'], array $relations = [])
 * @method bool deleteById(int $modelId)
 */
class ArticlesRepository extends BaseRepository implements PaginateRepositoryInterface
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $perPage
     * @param int $currentPage
     * @param string|null $sortBy
     * @param string $sortOrder
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 20, int $currentPage = 1, ?string $sortBy = null, string $sortOrder = 'asc'): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($sortBy) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate(perPage: $perPage, pageName: $currentPage);
    }
}

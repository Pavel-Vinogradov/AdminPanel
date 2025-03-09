<?php

declare(strict_types=1);

namespace App\Domain\Users\Repositories;

use App\Core\Repositories\BaseRepository;
use App\Core\Repositories\PaginateRepositoryInterface;
use App\Domain\Users\Entities\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @method User|null findById(int $modelId)
 * @method User create(array $attributes)
 * @method User|null update(int $modelId, array $attributes)
 * @method Collection <int, User> getAll(array $columns = ['*'], array $relations = [])
 * @method bool deleteById(int $modelId)
 */
class UserRepository extends BaseRepository implements PaginateRepositoryInterface
{
    public function __construct(User $model)
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

<?php

declare(strict_types=1);

namespace App\Domain\Users\Repositories;

use App\Core\Repositories\BaseRepository;
use App\Domain\Users\Entities\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method User|null findById(int $modelId)
 * @method User create(array $attributes)
 * @method User|null update(int $modelId, array $attributes)
 * @method Collection <int, User> getAll(array $columns = ['*'], array $relations = [])
 * @method bool deleteById(int $modelId)
 */
class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

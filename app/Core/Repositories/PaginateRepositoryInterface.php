<?php

declare(strict_types=1);

namespace App\Core\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface PaginateRepositoryInterface
{
    public function paginate(int $perPage = 20, int $currentPage = 1, array $columns = ['*']): LengthAwarePaginator;
}

<?php

declare(strict_types=1);

namespace App\Domain\Users\Services;

use App\Core\DTO\PaginationDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function paginate(PaginationDTO $paginationDTO): LengthAwarePaginator;

    public function findByUserId(int $id);
}

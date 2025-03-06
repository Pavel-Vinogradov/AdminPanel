<?php

declare(strict_types=1);

namespace App\Domain\Users\Services;

use App\Core\DTO\PaginationDTO;
use App\Domain\Users\Repositories\UserRepository;
use App\Domain\Users\Services\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class UserService implements UserServiceInterface
{

    public function __construct(
        private UserRepository $repository,
    ) {}

    public function paginate(PaginationDTO $paginationDTO): LengthAwarePaginator
    {
        return $this->repository->paginate(
            $paginationDTO->perPage,
            $paginationDTO->currentPage,
            $paginationDTO->sortBy,
            $paginationDTO->sortOrder

        );
    }
}

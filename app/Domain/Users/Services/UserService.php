<?php

declare(strict_types=1);

namespace App\Domain\Users\Services;

use App\Core\DTO\PaginationDTO;
use App\Domain\Users\Entities\User;
use App\Domain\Users\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * @param int $id
     * @return User
     */
    public function findByUserId(int $id): User
    {
        $user = $this->repository->findById($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        return $user;
    }
}

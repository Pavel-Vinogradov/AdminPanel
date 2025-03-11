<?php

namespace Tests\Unit;

use App\Domain\Users\Entities\User;
use App\Domain\Users\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected User $user;
    protected UserRepository $userRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createMock(User::class);
        $this->userRepository = new UserRepository($this->user);
    }

    /**
     * @throws Exception
     */
    public function testPaginate(): void
    {
        $perPage = 20;
        $currentPage = 1;
        $sortBy = 'created_at';
        $sortDirection = 'desc';
        $queryBuilderMock = $this->createMock(Builder::class);
        $paginatorMock = $this->createMock(LengthAwarePaginator::class);
        $this->user
            ->expects($this->once())
            ->method('newQuery')
            ->willReturn($queryBuilderMock);
        $queryBuilderMock
            ->expects($this->once())
            ->method('paginate')
            ->willReturn($paginatorMock);

        $result = $this->userRepository->paginate($perPage, $currentPage, $sortBy, $sortDirection);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

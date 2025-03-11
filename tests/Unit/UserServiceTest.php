<?php

namespace Tests\Unit;

use App\Core\DTO\PaginationDTO;
use App\Domain\Users\Repositories\UserRepository;
use App\Domain\Users\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

class UserServiceTest extends TestCase
{

    protected UserService $userService;
    protected UserRepository $userRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
        parent::setUp();
    }

    /**
     * @throws UnknownProperties
     * @throws Exception
     * @throws ValidationException
     */
    public function testPaginate(): void
    {
        $paginationDTO = new PaginationDTO(perPage: 20, currentPage: 1);
        $paginator = $this->createMock(LengthAwarePaginator::class);

        $this->userRepository
            ->expects($this->once())
            ->method('paginate')
            ->with($paginationDTO->perPage, $paginationDTO->currentPage)
            ->willReturn($paginator);

        $result = $this->userService->paginate($paginationDTO);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

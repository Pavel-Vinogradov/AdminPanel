<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\DTO\PaginationDTO;
use App\Domain\Articles\DTOs\ArticleDTO;
use App\Domain\Articles\Entities\Article;
use App\Domain\Articles\Repositories\ArticlesRepository;
use App\Domain\Articles\Services\ArticleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class ArticleServiceTest extends TestCase
{
    protected ArticlesRepository $articlesRepository;

    protected ArticleService $articleService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->articlesRepository = $this->createMock(ArticlesRepository::class);
        $this->articleService = new ArticleService($this->articlesRepository);
    }

    public function test_get_all(): void
    {
        $articles = new Collection([new Article(), new Article()]);
        $this->articlesRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn($articles);

        $result = $this->articleService->getAll();

        $this->assertCount(2, $result);
    }

    public function test_get_by_id_success(): void
    {
        $article = new Article();

        $this->articlesRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($article);

        $result = $this->articleService->getById(1);

        $this->assertInstanceOf(Article::class, $result);
    }

    public function test_get_by_id_not_found(): void
    {
        $this->articlesRepository
            ->expects($this->once())
            ->method('findById')
            ->with(999)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->articleService->getById(999);
    }

    /**
     * @throws Exception
     */
    public function test_create(): void
    {
        $articleDTO = $this->createMock(ArticleDTO::class);
        $articleData = ['title' => 'Test', 'content' => 'Test content'];

        $articleDTO
            ->expects($this->once())
            ->method('toArray')
            ->willReturn($articleData);

        $article = new Article();

        $this->articlesRepository
            ->expects($this->once())
            ->method('create')
            ->with($articleData)
            ->willReturn($article);

        $result = $this->articleService->create($articleDTO);

        $this->assertInstanceOf(Article::class, $result);
    }

    /**
     * @throws Exception
     */
    public function test_create_failure(): void
    {
        $articleDTO = $this->createMock(ArticleDTO::class);
        $articleData = ['title' => 'Test', 'content' => 'Test content'];
        $articleDTO
            ->expects($this->once())
            ->method('toArray')
            ->willReturn($articleData);

        $this->articlesRepository
            ->expects($this->once())
            ->method('create')
            ->with($articleData)
            ->willReturn(null);

        $this->expectException(BadRequestHttpException::class);

        $this->articleService->create($articleDTO);
    }

    /**
     * @throws Exception
     */
    public function test_update(): void
    {
        $articleDTO = $this->createMock(ArticleDTO::class);
        $articleData = ['title' => 'Test', 'content' => 'Test content'];
        $articleDTO
            ->expects($this->once())
            ->method('toArray')
            ->willReturn($articleData);

        $article = new Article();

        $this->articlesRepository
            ->expects($this->once())
            ->method('update')
            ->with(1, $articleData)
            ->willReturn($article);

        $result = $this->articleService->update(1, $articleDTO);

        $this->assertInstanceOf(Article::class, $result);
    }

    /**
     * @throws Exception
     */
    public function test_update_failure(): void
    {
        $articleDTO = $this->createMock(ArticleDTO::class);
        $articleData = ['title' => 'Test', 'content' => 'Test content'];
        $articleDTO
            ->expects($this->once())
            ->method('toArray')
            ->willReturn($articleData);

        $this->articlesRepository
            ->expects($this->once())
            ->method('update')
            ->with(999, $articleData)
            ->willReturn(null);

        $this->expectException(BadRequestHttpException::class);

        $this->articleService->update(999, $articleDTO);
    }

    public function test_delete_success(): void
    {
        $this->articlesRepository
            ->expects($this->once())
            ->method('deleteById')
            ->with(1)
            ->willReturn(true);

        $result = $this->articleService->delete(1);

        $this->assertTrue($result);
    }

    public function test_delete_not_found(): void
    {
        $this->articlesRepository
            ->expects($this->once())
            ->method('deleteById')
            ->with(999)
            ->willReturn(false);

        $this->expectException(NotFoundHttpException::class);

        $this->articleService->delete(999);
    }

    /**
     * @throws UnknownProperties
     * @throws Exception
     * @throws ValidationException
     */
    public function test_paginate(): void
    {
        $paginationDTO = new PaginationDTO(perPage: 20, currentPage: 1);
        $paginator = $this->createMock(LengthAwarePaginator::class);

        $this->articlesRepository
            ->expects($this->once())
            ->method('paginate')
            ->with($paginationDTO->perPage, $paginationDTO->currentPage)
            ->willReturn($paginator);

        $result = $this->articleService->paginate($paginationDTO);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

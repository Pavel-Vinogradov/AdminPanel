<?php

declare(strict_types=1);

namespace App\Domain\Articles\Repositories;

use App\Domain\Articles\Entities\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class ArticlesRepositoryTest extends TestCase
{
    protected Article $article;

    protected ArticlesRepository $articlesRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->article = $this->createMock(Article::class);
        $this->articlesRepository = new ArticlesRepository($this->article);
    }

    /**
     * @throws Exception
     */
    public function test_paginate(): void
    {
        $perPage = 20;
        $currentPage = 1;
        $columns = ['*'];
        $queryBuilderMock = $this->createMock(Builder::class);
        $paginatorMock = $this->createMock(LengthAwarePaginator::class);
        $this->article
            ->expects($this->once())
            ->method('newQuery')
            ->willReturn($queryBuilderMock);
        $queryBuilderMock
            ->expects($this->once())
            ->method('paginate')
            ->with($perPage, $columns, $currentPage)
            ->willReturn($paginatorMock);

        $result = $this->articlesRepository->paginate($perPage, $currentPage, $columns);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

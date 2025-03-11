<?php

declare(strict_types=1);

namespace App\Domain\Articles\Services;

use App\Core\DTO\PaginationDTO;
use App\Domain\Articles\DTOs\ArticleDTO;
use App\Domain\Articles\Entities\Article;
use App\Domain\Articles\Repositories\ArticlesRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ArticleService implements ArticleServiceInterface
{
    public function __construct(
        private ArticlesRepository $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getById($id): Article
    {
        $article = $this->repository->findById($id);
        if (! $article) {
            throw new NotFoundHttpException();
        }

        return $article;
    }

    public function create(ArticleDTO $articleDTO): Article
    {
        $article = $this->repository->create($articleDTO->toArray());
        if (! $article) {
            throw new BadRequestHttpException();
        }

        return $article;
    }

    public function update($id, ArticleDTO $articleDTO): Article
    {
        $filteredArray = array_filter($articleDTO->toArray(), static fn ($value) => $value !== null);
        $article = $this->repository->update($id, $filteredArray);
        if (! $article) {
            throw new BadRequestHttpException();
        }

        return $article;
    }

    public function delete($id): bool
    {
        $article = $this->repository->deleteById($id);
        if (! $article) {
            throw new NotFoundHttpException();
        }

        return $article;
    }

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

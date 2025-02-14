<?php

declare(strict_types=1);

namespace App\Domain\Articles\Services;

use App\Core\DTO\PaginationDTO;
use App\Domain\Articles\DTOs\ArticleDTO;
use App\Domain\Articles\Entities\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleServiceInterface
{
    public function getAll(): Collection;

    public function getById($id): Article;

    public function create(ArticleDTO $articleDTO): Article;

    public function update($id, ArticleDTO $articleDTO): Article;

    public function delete($id): bool;

    public function paginate(PaginationDTO $paginationDTO): LengthAwarePaginator;
}

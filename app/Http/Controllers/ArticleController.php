<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\DTO\PaginationDTO;
use App\Domain\Articles\DTOs\ArticleDTO;
use App\Domain\Articles\Request\ArticleRequest;
use App\Domain\Articles\Services\ArticleServiceInterface;
use App\Domain\Comments\Services\CommentServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleServiceInterface $articleService,
        private readonly CommentServiceInterface $commentService,
    ) {
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        $dto = new PaginationDTO($request->toArray());
        $articles = $this->articleService->paginate($dto);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        return view('articles.create');
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function store(ArticleRequest $request): RedirectResponse
    {
        $filePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('articles', $fileName, 's3');
        }

        $dto = new ArticleDTO($request->toArray());
        $dto->image = $filePath;
        $this->articleService->create($dto);

        return redirect()->route('dashboard.articles.index')->with('success', 'Статья успешно создана!');
    }

    /**
     * Display the specified article.
     */
    public function show(int $id): View
    {
        $article = $this->articleService->getById($id);
        $article->increment('views');
        $comments = $this->commentService->getCommentsForArticle($article->id);

        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(int $id): View
    {
        $article = $this->articleService->getById($id);

        return view('articles.edit', compact('article'));
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function update(ArticleRequest $request, int $id): View
    {
        $dto = new ArticleDTO($request->toArray());
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('articles', $fileName, 's3');
            $dto->image = $filePath;
        }

        $article = $this->articleService->update($id, $dto);

        return view('articles.edit', compact('article'));
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->articleService->delete($id);

        return redirect()->route('dashboard.articles.index')->with('success', 'Статья успешно удалена!');
    }

    /**
     * Display a listing of the articles on the public site.
     *
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function publicIndex(Request $request): View
    {
        $paginationDTO = PaginationDTO::fromRequest($request->all());
        $articles = $this->articleService->paginate($paginationDTO);

        return view('site.articles.index', compact('articles'));
    }
}

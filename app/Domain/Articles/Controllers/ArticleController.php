<?php

declare(strict_types=1);

namespace App\Domain\Articles\Controllers;

use App\Core\DTO\PaginationDTO;
use App\Domain\Articles\DTOs\ArticleDTO;
use App\Domain\Articles\Request\ArticleRequest;
use App\Domain\Articles\Services\ArticleServiceInterface;
use App\Domain\Comments\Services\CommentServiceInterface;
use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use App\Domain\Statistic\Services\ViewStatisticInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleServiceInterface $articleService,
        private readonly CommentServiceInterface $commentService,
        private readonly ViewStatisticInterface  $viewStaticService,
    ){}

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function index(Request $request): View
    {
        $dto = PaginationDTO::fromRequest($request->toArray());
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function show(int $id, Request $request): View
    {
        $article = $this->articleService->getById($id);
        $comments = $this->commentService->getCommentsForArticle($article->id);
        $viewStatic = new ViewStatisticDTO([
            'ip_address' => $request->ip(),
            'browser' => $request->header('User-Agent'),
            'article_id' => $article->id,
            'user_id' => Auth::user()->id,
        ]);
        $this->viewStaticService->create($viewStatic);
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

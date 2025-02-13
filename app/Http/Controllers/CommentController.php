<?php

namespace App\Http\Controllers;

use App\Domain\Comments\DTOs\CommentDTO;
use App\Domain\Comments\Request\CommentRequest;
use App\Domain\Comments\Services\CommentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentServiceInterface $service,
    ) {}

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function store(CommentRequest $request): JsonResponse
    {
        $dto = new CommentDTO($request->toArray());
        $dto->article_id = $request->article_id;
        $dto->user_id = Auth::check() ? Auth::id() : null;

        return response()->json($this->service->create($dto));
    }

    public function show(int $articleId): JsonResponse
    {
        $comments = $this->service->getCommentsForArticle($articleId);

        return response()->json($comments);
    }
}

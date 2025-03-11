<?php

declare(strict_types=1);

namespace App\Domain\Comments\Controllers;

use App\Domain\Comments\DTOs\CommentDTO;
use App\Domain\Comments\Request\CommentRequest;
use App\Domain\Comments\Resources\CommentResource;
use App\Domain\Comments\Services\CommentServiceInterface;
use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use App\Domain\Statistic\Services\ViewStatisticInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentServiceInterface $service,
        public readonly ViewStatisticInterface   $viewStatisticService,
    )
    {
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function store(CommentRequest $request): CommentResource
    {
        $dto = new CommentDTO($request->toArray());
        $comment = $this->service->create($dto);
        $viewStatic = new ViewStatisticDTO([
            'ip_address' => $request->ip(),
            'browser' => $request->header('User-Agent'),
            'comment_id' => $comment->id,
            'article_id' => $dto->article_id,
            'user_id' => $dto->user_id,
        ]);
        $this->viewStatisticService->create($viewStatic);
        return new CommentResource($comment);
    }

    public function show(int $articleId): AnonymousResourceCollection
    {
        $comments = $this->service->getCommentsForArticle($articleId);

        return CommentResource::collection($comments);
    }
}

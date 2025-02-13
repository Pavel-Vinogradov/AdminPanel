<?php

namespace Unit;

use App\Domain\Comments\DTOs\CommentDTO;
use App\Domain\Comments\Entities\Comment;
use App\Domain\Comments\Events\CommentAddedEvent;
use App\Domain\Comments\Notifications\NewCommentNotification;
use App\Domain\Comments\Repositories\CommentRepository;
use App\Domain\Comments\Services\CommentService;
use App\Domain\Users\Entities\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tests\TestCase;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class CommentServiceTest extends TestCase
{
    protected CommentService $commentService;

    protected CommentRepository $commentRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->commentRepository = $this->createMock(CommentRepository::class);
        $this->commentService = new CommentService($this->commentRepository);
    }

    public function test_get_comments_for_article(): void
    {
        $articleId = 1;
        $comments = new Collection([new Comment()]);

        $this->commentRepository
            ->expects($this->once())
            ->method('getCommentsForArticle')
            ->with($articleId)
            ->willReturn($comments);

        $result = $this->commentService->getCommentsForArticle($articleId);

        $this->assertSame($comments, $result);
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function test_create(): void
    {
        Event::fake();
        Notification::fake();

        $commentDTO = new CommentDTO([
            'article_id' => 1,
            'user_id' => 2,
            'parent_id' => null,
            'body' => 'Test comment',
        ]);

        $comment = new Comment();
        $comment->id = 1;
        $comment->article_id = 1;
        $comment->user_id = 2;
        $comment->parent_id = null;
        $comment->body = 'Test comment';

        $this->commentRepository
            ->expects($this->once())
            ->method('create')
            ->with($commentDTO->toArray())
            ->willReturn($comment);

        $result = $this->commentService->create($commentDTO);

        $this->assertInstanceOf(Comment::class, $result);
        $this->assertEquals(1, $result->id);

        Event::assertDispatched(CommentAddedEvent::class);
        Notification::assertNothingSent();
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function test_create_fail(): void
    {
        Event::fake();
        Notification::fake();

        $commentDTO = new CommentDTO([
            'article_id' => 1,
            'user_id' => 2,
            'parent_id' => null,
            'body' => 'Test comment',
        ]);

        $comment = new Comment();
        $comment->id = 1;
        $comment->article_id = 1;
        $comment->user_id = 2;
        $comment->parent_id = null;
        $comment->body = 'Test comment';

        $this->commentRepository
            ->expects($this->once())
            ->method('create')
            ->with($commentDTO->toArray())
            ->willReturn(null);

        $this->expectException(BadRequestHttpException::class);

        $result = $this->commentService->create($commentDTO);
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function test_create_with_parent_comment(): void
    {
        Event::fake();
        Notification::fake();

        $parentUser = new User();
        $parentUser->id = 3;

        $parentComment = new Comment();
        $parentComment->id = 1;
        $parentComment->user_id = 3;
        $parentComment->article_id = 1;
        $parentComment->setRelation('user', $parentUser);

        $commentDTO = new CommentDTO([
            'article_id' => 1,
            'user_id' => 2,
            'parent_id' => 1,
            'body' => 'Reply to comment',
        ]);

        $comment = new Comment();
        $comment->id = 2;
        $comment->article_id = 1;
        $comment->user_id = 2;
        $comment->parent_id = 1;
        $comment->body = 'Reply to comment';

        $this->commentRepository
            ->expects($this->once())
            ->method('create')
            ->with($commentDTO->toArray())
            ->willReturn($comment);

        $this->commentRepository
            ->expects($this->once())
            ->method('findById')
            ->with($comment->parent_id)
            ->willReturn($parentComment);

        Auth::shouldReceive('id')->once()->andReturn(2);

        $result = $this->commentService->create($commentDTO);

        $this->assertInstanceOf(Comment::class, $result);
        $this->assertEquals(2, $result->id);

        Event::assertDispatched(CommentAddedEvent::class);
        Notification::assertSentTo($parentUser, NewCommentNotification::class);
    }
}

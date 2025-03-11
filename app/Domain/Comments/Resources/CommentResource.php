<?php

declare(strict_types=1);

namespace App\Domain\Comments\Resources;

use App\Domain\Comments\Entities\Comment;
use App\Domain\Users\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
final class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'article_id' => $this->article_id,
            'body' => $this->body,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'parent_id' => $this->parent_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user_id' => $this->user_id,
            'user' => $this->user ? new UserResource($this->user) : null,
        ];
    }
}

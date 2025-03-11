<?php

declare(strict_types=1);

namespace App\Domain\Comments\Request;

use App\Core\Request\BaseRequest;

final class CommentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'string'],
            'article_id' => ['string', 'exists:articles,id'],
        ];
    }
}

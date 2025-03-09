<?php

declare(strict_types=1);

namespace App\Domain\Articles\Request;

use App\Core\Request\BaseRequest;

final class ArticleRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png',
        ];
    }
}

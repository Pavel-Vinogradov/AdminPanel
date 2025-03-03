<?php

declare(strict_types=1);

namespace App\Domain\Statistic\DTOs;

use Tizix\DataTransferObject\DataTransferObject;

final class ViewStatisticDTO extends DataTransferObject
{
    public string|int|null $article_id;
    public ?string $browser;

    public ?string $ip_address;

    public string|int|null $user_id;

    public string|int|null $comment_id;

}

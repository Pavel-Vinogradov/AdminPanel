<?php

declare(strict_types=1);

namespace App\Domain\Statistic\DTOs;

use Tizix\DataTransferObject\DataTransferObject;

final class ViewStatisticIndexDTO extends DataTransferObject
{
    public string $range = 'day';

}

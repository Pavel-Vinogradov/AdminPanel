<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Repositories;

use App\Domain\Statistic\Entities\ViewStatistic;

readonly class ViewStatisticRepository
{
    public function __construct(private ViewStatistic $statistic) {}

    public function create(array $data): ?ViewStatistic
    {
        return $this->statistic
            ->newQuery()
            ->create($data);
    }


}

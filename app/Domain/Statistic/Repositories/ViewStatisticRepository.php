<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Repositories;

use App\Domain\Statistic\Entities\ViewStatistic;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Domain\Statistic\Entities\_IH_ViewStatistic_C;

readonly class ViewStatisticRepository
{
    public function __construct(private ViewStatistic $statistic)
    {
    }

    public function create(array $data): ?ViewStatistic
    {
        return $this->statistic
            ->newQuery()
            ->create($data);
    }

    /**
     * @param string $range
     * @return Collection<int,ViewStatistic>
     */
    public function getStatistics(string $range): Collection
    {
        $query = $this->statistic->newQuery();
        $now = now();
        $dateFilter = match ($range) {
            'week' => $now->startOfWeek(),
            'month' => $now->startOfMonth(),
            default => $now->startOfDay(),
        };

        return $query
            ->where('viewed_at', '>=', $dateFilter)
            ->groupBy('viewed_at')
            ->orderBy('viewed_at', 'asc')
            ->get(['viewed_at', 'article_id']);

    }


}

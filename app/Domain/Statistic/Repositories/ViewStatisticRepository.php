<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Repositories;

use App\Domain\Statistic\Entities\ViewStatistic;
use Illuminate\Database\Eloquent\Collection;

readonly class ViewStatisticRepository
{
    public function __construct(private ViewStatistic $statistic) {}

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

        match ($range) {
            'week' => $query->where('viewed_at', '>=', $now->startOfWeek()),
            'month' => $query->where('viewed_at', '>=', $now->startOfMonth()),
            default => $query->whereBetween('viewed_at', [$now->startOfDay(), $now->endOfDay()]),
        };

        return $query
            ->raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            '_id' => [
                                '$dateToString' => [
                                    'format' => "%Y-%m-%d",
                                    'date' => '$viewed_at'
                                ]
                            ],
                            'views' => ['$sum' => 1],
                            'comments' => ['$sum' => ['$cond' => [['$ifNull' => ['$comment_id', false]], 1, 0]]]
                        ]
                    ],
                    ['$sort' => ['_id' => 1]]
                ]);
            });
    }



}

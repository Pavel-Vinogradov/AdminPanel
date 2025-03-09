<?php

namespace Database\Factories\Domain\Statistic\Entities;

use App\Domain\Statistic\Entities\ViewStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ViewStatisticFactory extends Factory
{
    protected $model = ViewStatistic::class;

    public function definition(): array
    {
        return [
            'article_id' => $this->faker->numberBetween(1, 10),
            'viewed_at' => $this->faker->dateTimeThisYear(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'ip' => $this->faker->ipv4,
            'browser' => $this->faker->userAgent,
        ];
    }
}

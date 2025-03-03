<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Services;

use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use App\Domain\Statistic\DTOs\ViewStatisticIndexDTO;
use App\Domain\Statistic\Entities\ViewStatistic;

interface ViewStatisticInterface
{
    public function create(ViewStatisticDTO $statisticDTO): ViewStatistic;

    public function index(ViewStatisticIndexDTO $indexDTO);
}

<?php

namespace App\Domain\Statistic\Services;

use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use App\Domain\Statistic\DTOs\ViewStatisticIndexDTO;
use App\Domain\Statistic\Entities\ViewStatistic;
use App\Domain\Statistic\Repositories\ViewStatisticRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

readonly class ViewStatisticService implements ViewStatisticInterface
{

    public function __construct(
        private ViewStatisticRepository $viewStatisticRepository
    ){}


    /**
     * @param ViewStatisticDTO $statisticDTO
     * @return ViewStatistic
     */
    public function create(ViewStatisticDTO $statisticDTO): ViewStatistic
    {
        $viewStatistic = $this->viewStatisticRepository->create($statisticDTO->toArray());
        if (!$viewStatistic) {
            throw new BadRequestException();
        }
        return $viewStatistic;
    }

    /**
     * @param ViewStatisticIndexDTO $indexDTO
     * @return Collection
     */
    public function index(ViewStatisticIndexDTO $indexDTO): Collection
    {
        return $this->viewStatisticRepository->getStatistics($indexDTO->range);
    }
}

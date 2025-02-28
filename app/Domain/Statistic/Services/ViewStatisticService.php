<?php

namespace App\Domain\Statistic\Services;

use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use App\Domain\Statistic\Entities\ViewStatistic;
use App\Domain\Statistic\Repositories\ViewStatisticRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

readonly class ViewStatisticService implements ViewStatisticInterface
{

    public function __construct(
        private ViewStatisticRepository $viewStatisticRepository
    ){}

    /**
     * @param ViewStatisticDTO $viewStatistic
     * @return ViewStatistic
     */
    public function create(ViewStatisticDTO $viewStatistic): ViewStatistic
    {
        $viewStatistic = $this->viewStatisticRepository->create($viewStatistic->toArray());
        if (!$viewStatistic) {
            throw new BadRequestException();
        }
        return $viewStatistic;
    }
}

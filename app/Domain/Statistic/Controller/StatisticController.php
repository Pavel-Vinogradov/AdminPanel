<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Controller;

use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use App\Domain\Statistic\DTOs\ViewStatisticIndexDTO;
use App\Domain\Statistic\Repositories\ViewStatisticRepository;
use App\Domain\Statistic\Services\ViewStatisticInterface;
use App\Domain\Statistic\Services\ViewStatisticService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class StatisticController extends Controller
{
    public function __construct(
        private readonly ViewStatisticInterface $viewStatistic
    ) {}

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->expectsJson()) {
            $dto = new ViewStatisticIndexDTO($request->only('range'));
            $stats = $this->viewStatistic->index($dto);
            return response()->json($stats);
        }

        return view('statistics.index');
    }

}

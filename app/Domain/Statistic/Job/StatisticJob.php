<?php

namespace App\Domain\Statistic\Job;

use App\Domain\Statistic\DTOs\ViewStatisticDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StatisticJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly ViewStatisticDTO $DTO)
    {
    }

    public function handle(): void
    {
        \Log::log('StatisticJob', ['data' => $this->DTO->toArray()]);
    }
}

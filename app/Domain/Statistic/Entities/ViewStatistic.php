<?php

declare(strict_types=1);

namespace App\Domain\Statistic\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

final class ViewStatistic extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected string $collection = 'view_statistics';

    protected $fillable = [
        'article_id',
        'viewed_at',
        'user_id',
        'ip_address',
        'browser',
        'comment_id'
    ];

    protected array $dates = [
        'viewed_at'
    ];

    protected static function boot(): void
    {
        parent::boot();
        self::creating(static function ($model) {
            if (is_null($model->viewed_at)) {
                $model->viewed_at = now();
            }
        });
    }

}

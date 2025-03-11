<?php

declare(strict_types=1);

namespace App\Domain\Comments\Entities;

use App\Domain\Comments\Observers\CommentObserver;
use App\Domain\Users\Entities\User;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;

/**
 * @property mixed $id 9 occurrences
 * @property string|null $article_id 9 occurrences
 * @property string|null $body 9 occurrences
 * @property Carbon|null $created_at 9 occurrences
 * @property string|null $parent_id 9 occurrences
 * @property Carbon|null $updated_at 9 occurrences
 * @property string|null $user_id 9 occurrences
 *
 * @mixin Eloquent
 */
final class Comment extends Model
{
    use HasFactory;

    public ?User $user = null;

    protected string $collection = 'comments';

    protected $connection = 'mongodb';

    protected $fillable = [
        'article_id', 'user_id', 'body', 'parent_id',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', '_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::observe(CommentObserver::class);
        self::saving(static function ($model): void {
            $model->created_at = Carbon::now('Europe/Moscow');
            $model->updated_at = Carbon::now('Europe/Moscow');
        });
    }
}

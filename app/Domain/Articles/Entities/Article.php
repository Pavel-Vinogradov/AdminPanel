<?php

namespace App\Domain\Articles\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $image
 * @property int $views
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|Article newModelQuery()
 * @method static Builder<static>|Article newQuery()
 * @method static Builder<static>|Article query()
 * @method static Builder<static>|Article whereContent($value)
 * @method static Builder<static>|Article whereCreatedAt($value)
 * @method static Builder<static>|Article whereId($value)
 * @method static Builder<static>|Article whereImage($value)
 * @method static Builder<static>|Article whereTitle($value)
 * @method static Builder<static>|Article whereUpdatedAt($value)
 * @method static Builder<static>|Article whereViews($value)
 *
 * @mixin Eloquent
 */
class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'title', 'content', 'image', 'views',
    ];

    public function newEloquentBuilder($query): ArticleBuilder
    {
        return new ArticleBuilder($query);
    }
}

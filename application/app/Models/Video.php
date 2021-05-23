<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Video
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property bool $is_free
 * @property int $purchase_duration
 *
 * @method static Builder paidByUser(User $user)
 * @method static Builder free()
 * @method static Builder byUser(User $user)
 */
class Video extends Model
{
    public $timestamps = false;

    public $fillable = [
        'title',
        'is_free',
        'purchase_duration',
    ];

    public $casts = [
        'is_free' => 'boolean',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_video');
    }

    public function scopePaidByUser(Builder $query, User $user): Builder
    {
        $dateAdd = 'date_add(subscriptions.time_start_at, interval videos.purchase_duration second)';

        return $query->select('videos.*')
            ->where('videos.is_free', 0)
            ->crossJoin('package_video', 'videos.id', '=', 'package_video.video_id')
            ->crossJoin('subscriptions', 'package_video.package_id', '=', 'subscriptions.package_id')
            ->where('subscriptions.user_id', $user->id)
            ->whereRaw(sprintf('%s > now()', $dateAdd))
            ->groupBy(['videos.id', 'videos.title', 'videos.is_free', 'videos.purchase_duration'])
            ->orderByRaw(sprintf('max(%s) desc', $dateAdd));
    }

    public static function scopeFree(Builder $query): Builder
    {
        return $query->where('is_free', 1);
    }

    public static function scopeByUser(Builder $query, User $user): Builder
    {
        return static::paidByUser($user)
            ->selectRaw('max(date_add(subscriptions.time_start_at, interval videos.purchase_duration second)) as time_stop_at')
            ->union(
                static::free()->select('videos.*')
                    ->selectRaw('date_add(now(), interval purchase_duration second) as time_stop_at')
                    ->getQuery()
            )
            ->orderBy('is_free')
            ->orderByDesc('time_stop_at');
    }
}

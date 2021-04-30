<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Video
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property bool $is_free
 * @property int $purchase_duration
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

    public static function getByUser(User $user, int $limit = 0, int $offset = 0): Collection
    {
        $with = static::getByUserWithSql($user);
        $sql = $with . ' select * from distinct_user_videos';

        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        if ($offset) {
            $sql .= ' OFFSET ' . $offset;
        }

        return static::query()->fromQuery($sql);
    }

    public static function countByUser(User $user): int
    {
        $with = static::getByUserWithSql($user);
        $result = DB::selectOne($with . ' select count(*) as count from distinct_user_videos');
        return $result->count;
    }

    private static function getByUserWithSql(User $user): string
    {
        $videos = static::getUserVideoSql($user);
        return <<<SQL
with user_videos as (
    {$videos}
), distinct_user_videos as (
    select * from user_videos
    group by user_videos.id, user_videos.title, user_videos.is_free, user_videos.purchase_duration
)
SQL;
    }

    private static function getUserVideoSql(User $user): string
    {
        return <<<SQL
select videos.*
    from video_service.videos
             inner join video_service.package_video on videos.id = package_video.video_id
             inner join video_service.packages on package_video.package_id = packages.id
             inner join video_service.subscriptions on packages.id = subscriptions.package_id
    where subscriptions.user_id = {$user->id}
      and subscriptions.time_start_at + INTERVAL videos.purchase_duration SECOND > now()
    order by subscriptions.time_start_at + INTERVAL videos.purchase_duration SECOND desc
SQL;
    }
}

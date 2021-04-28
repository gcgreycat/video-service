<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Subscription
 * @package App\Models
 * @property int $id
 * @property DateTime $time_start_at
 * @property int $package_id
 * @property int $user_id
 */
class Subscription extends Model
{
    public $timestamps = false;

    public $fillable = [
        'time_start_at',
        'package_id',
        'user_id',
    ];

    public $casts = [
        'time_start_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}

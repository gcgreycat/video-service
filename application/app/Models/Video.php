<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Video
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property bool $is_free
 * @property DateTime $purchase_duration
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
        'purchase_duration' => 'datetime',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_video');
    }
}

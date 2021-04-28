<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Package
 * @package App\Models
 * @property int $id
 * @property string $title
 */
class Package extends Model
{
    public $timestamps = false;

    public $fillable = ['title'];

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_video');
    }
}

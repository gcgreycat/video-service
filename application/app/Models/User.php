<?php

namespace App\Models;

use DateInterval;
use DateTime;
use Exception;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $token
 * @property Carbon $token_expired_at
 */
class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'token',
        'token_expired_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'token_expired_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->rememberTokenName = '';
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'subscriptions');
    }

    /**
     * @throws Exception
     */
    public function regenerateToken(): void
    {
        $this->token = bin2hex(random_bytes(16));
        $this->token_expired_at = (new DateTime())->add(new DateInterval('PT1H'));
    }
}

<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id;
    }

    public function delete(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id;
    }
}

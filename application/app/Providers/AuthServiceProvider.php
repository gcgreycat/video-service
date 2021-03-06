<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Policies\SubscriptionPolicy;
use App\Services\Auth\JsonApiGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Subscription::class => SubscriptionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('json-api', function (Application $app, $name, array $config) {
            return new JsonApiGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });
    }
}

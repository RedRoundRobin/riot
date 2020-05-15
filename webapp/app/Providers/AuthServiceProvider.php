<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* define a admin user role */
        Gate::define('isAdmin', function ($user) {
            return $user->type == 2;
        });

        /* define a manager user role */
        Gate::define('isMod', function ($user) {
            return $user->type == 1;
        });

        /* define a user role */
        Gate::define('isUser', function ($user) {
            return $user->type == 0;
        });

        Auth::provider('custom', function () {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return resolve(UserServiceProvider::class);
        });
    }
}

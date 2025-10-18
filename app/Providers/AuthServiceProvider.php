<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Định nghĩa Gate cho các quyền
        Gate::define('admin', function ($user) {
            return $user->ID_quyen == 1;
        });

        Gate::define('manager', function ($user) {
            return $user->ID_quyen == 2;
        });

        Gate::define('employee', function ($user) {
            return $user->ID_quyen == 3;
        });
    }
}

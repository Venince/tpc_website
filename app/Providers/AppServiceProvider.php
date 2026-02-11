<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('access-admin', function (User $user) {
            return (bool) ($user->is_admin || $user->is_super_admin);
        });

        Gate::define('manage-admins', function (User $user) {
            return (bool) $user->is_super_admin;
        });
    }
}

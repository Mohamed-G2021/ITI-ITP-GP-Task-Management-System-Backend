<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


use App\Models\UserMember;
use App\Models\UserWorkspace;
use App\Models\UserBoard;
use App\Models\UserCard;

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
        Gate::define('is-owner', function (UserMember $user) {
            return $user->role === 'owner';
        });
        Gate::define('is-admin', function (UserMember $user) {
            return $user->role === 'admin';
        });
        Gate::define('is-workspace-member', function (UserWorkspace $user) {
            return $user->role === 'workspace-member';
        });
        Gate::define('is-board-member', function (UserBoard $user) {
            return $user->role === 'board-member';
        });
        Gate::define('is-card-member', function (UserCard $user) {
            return $user->role === 'card-member';
        });
    }
}

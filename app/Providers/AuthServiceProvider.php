<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Organization;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Policies\OrganizationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        Organization::class => OrganizationPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        Gate::define('add-user', function (User $user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        Gate::define('edit-user', function (User $user, User $userModel) {
            if ($user->isOrganization() && $user->organization->id === $userModel->organization->id)
                return true;
        });
        Gate::define('delete-user', function (User $user, User $userModel) {
            if ($user->isOrganization() && $user->organization->id === $userModel->organization->id)
                return true;
        });


        Gate::define('edit_post', function (User $user, Post $post) {
            if ($user->id === $post->user_id) return true;

            if ($user->isOrganization() && $post->user->organization->id ==  $user->organization->id) {
                return true;
            }
        });

        Gate::define('delete_post', function (User $user, Post $post) {
            if ($user->id === $post->user_id) return true;

            if ($user->isOrganization() && $post->user->organization->id ==  $user->organization->id) {
                return true;
            }
        });

        Gate::define('add_post', function (User $user) {
            if ($user->isOrganization() || $user->isWriter() || $user->isAdmin()) {
                return true;
            }
        });

        Gate::define('edit_organization', function (User $user) {
            if ($user->isOrganization() || $user->isAdmin()) {
                return true;
            }
        });
        Gate::define('delete_organization', function (User $user) {
            if ($user->isOrganization() || $user->isAdmin()) {
                return true;
            }
        });
    }
}

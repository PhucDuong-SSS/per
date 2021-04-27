<?php


namespace App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;


use App\Models\User;

use App\Models\Post;

use App\Models\Organization;

use App\Models\Permission;



use App\Policies\PostPolicy;

use App\Policies\UserPolicy;

use App\Policies\OrganizationPolicy;

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

        Organization::class => OrganizationPolicy::class,

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

            if ($user->isAdmin()) {

                return true;

            }

        });


//        if(!$this->app->runningInConsole()) {

//            foreach (Permission::all() as $permission) {

//                Gate::define($permission->name, function ($user) use ($permission) {

//                    if($user->hasPermission($permission))

//                    {

//                        return true;

//                    }

//                });

//            }

//        }


        Gate::define('add_user', function (User $user) {

            if ($user->isOrganizationAdmin()) {

                return true;

            }

        });


        Gate::define('edit_user', function (User $user, User $model) {

            if($user->id === $model->id) return true;

            if ($user->isOrganizationAdmin() && $model->organization->id ==  $user->organization->id) {

                return true;

            }

        });


        Gate::define('delete_user', function (User $user, User $model) {

            if($user->id === $model->id) return true;

            if ($user->isOrganizationAdmin() && $model->organization->id ==  $user->organization->id) {

                return true;

            }

        });


        Gate::define('get_list_post', function (User $user, Post $post) {

            if($user->id === $post->user_id) return true;

            if ($user->isOrganizationAdmin() && $post->user->organization->id ==  $user->organization->id) {

                return true;

            }

        });


        Gate::define('edit_post', function (User $user, Post $post) {

            if($user->id === $post->user_id) return true;


            if ($user->isOrganizationAdmin() && $post->user->organization->id ==  $user->organization->id) {

                return true;

            }

        });


        Gate::define('delete_post', function (User $user, Post $post) {

            if($user->id === $post->user_id) return true;


            if ($user->isOrganizationAdmin() && $post->user->organization->id ==  $user->organization->id) {

                return true;

            }

        });


        Gate::define('add_post', function (User $user) {

            if ($user->isOrganizationAdmin() || $user->isWriter() || $user->isAdmin()) {

                return true;

            }

        });


        Gate::define('add_organization', function (User $user) {


        });


        Gate::define('edit_organization', function (User $user) {

            if ($user->isOrganizationAdmin() || $user->isWriter() || $user->isAdmin()) {

                return true;

            }

        });


        Gate::define('delete_organization', function (User $user) {

            if ($user->isOrganizationAdmin() || $user->isWriter() || $user->isAdmin()) {

                return true;

            }

        });


    }

}

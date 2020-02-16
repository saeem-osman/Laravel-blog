<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policy\BlogPostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
        //  'App\BlogPost' => 'App\Policies\BlogPostPolicy',
        App\BlogPost::class => BlogPostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // for simple Auth
        Gate::define('home.secret', function($user){
            return $user->is_admin;
        });
        // Gate::define('update-post', function($user, $post){
        //     return $user->id === $post->user_id;
        // });
        // Gate::define('delete-post', function($user, $post){
        //     return $user->id === $post->user_id;
        // });
        //use policies if we have a huge bunch of authorization
        // Gate::define('posts.update', 'App\Policies\BlogPostPolicy@update');
        // Gate::define('posts.delete', 'App\Policies\BlogPostPolicy@delete');

        //resource will get all default action out of the box
        // Gate::resource('posts', 'App\Policies\BlogPostPolicy');


        // Gate::before(function($user, $ability){
        //     if($user->is_admin && in_array($ability, ['posts.update'])){
        //         return true;
        //     }
        // });
        // Gate::after(function($user, $ability,$result){
        //     //we can still modify our result
        //     if($user->is_admin){
                
        //         return true;
        //     }
        // });
    }
}

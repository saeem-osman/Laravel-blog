<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Schema;
use App\BlogPost;
use App\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Blade::component('components.badge','badge');
        Blade::component('components.updated','updated');
        Blade::component('components.card','card');
        Blade::component('components.tags','tags');
        Blade::component('components.errors','errors');
        Blade::component('components.comment-form', 'commentForm');
        Blade::component('components.comment-list','commentList');


        // view()->composer('posts.index',ActivityComposer::class );
        // view()->composer('posts.show', ActivityComposer::class);
        //make this available everywhere
        // view()->composer('*', ActivityComposer::class);

        view()->composer(['posts.show','posts.index'], ActivityComposer::class);

        //
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);


    }
}

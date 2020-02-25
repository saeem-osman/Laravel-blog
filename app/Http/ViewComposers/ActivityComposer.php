<?php

namespace App\Http\ViewComposers;
use Illuminate\Support\Facades\Cache;
use App\BlogPost;
use App\User;
use Illuminate\View\View;

class ActivityComposer {
    public function compose(View $view){
        $most_commented = Cache::tags(['blog-posts'])->remember('most_commented', now()->addSeconds(10), function(){
            return BlogPost::mostCommented()->take(5)->get();
        });
        $most_active = Cache::tags(['blog-posts'])->remember('most_active', now()->addSeconds(10), function(){
            return User::withMostBlogPosts()->take(5)->get();
        });
        $last_month_user = Cache::tags(['blog-posts'])->remember('last_month_user', now()->addSeconds(10), function(){
            return User::withMostActivityLastMonth()->take(5)->get();
        });

        $view->with('most_commented',$most_commented);
        $view->with('most_active',$most_active);
        $view->with('last_month_user',$last_month_user);
    }
}



?>
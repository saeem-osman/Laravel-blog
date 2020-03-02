<?php

namespace App\Observers;

use App\BlogPost;
use Illuminate\Support\Facades\Cache;

class BlogPostObserver
{
    public function updating(BlogPost $blogPost)
    {
        Cache::tags(['blog-post'])->forget('blog-post-{post->id}');
    }

    public function deleting(BlogPost $blogPost){
        
        $post->comments()->delete();
        Cache::tags(['blog-post'])->forget('blog-post-{$post->id}');
    }

    public function restoring(BlogPost $blogPost)
    {
        $post->comments()->restore();
    }
}

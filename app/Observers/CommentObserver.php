<?php

namespace App\Observers;

use App\Comment;
use Illuminate\Support\Facades\Cache;


class CommentObserver
{
    public function creating(Comment $comment){
        
        if($comment->commentable_type === 'App\BlogPost'){
            Cache::tags(['blog-post'])->forget('blog-post-{$comment->commentable_id}');
            Cache::tags(['blog-post'])->forget('most_commented');
        }
    }
}
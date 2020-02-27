<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LatestScope;
use Illuminate\Support\Facades\Cache;
use App\Traits\Taggable;


class Comment extends Model
{
    //
    protected $fillable = ['user_id','content'];
    use SoftDeletes, Taggable;
    public function commentable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

    // public function tags(){
        //not used now
    //     return $this->morphToMany('App\Tag','taggable')->withTimeStamps();
    // }

    public static function boot(){
        parent::boot();
        // static::addGlobalScope(new LatestScope);
        static::creating(function(Comment $comment){
            if($comment->commentable_type === BlogPost::class){
                Cache::tags(['blog-post'])->forget('blog-post-{$comment->commentable_id}');
                Cache::tags(['blog-post'])->forget('most_commented');
            }
           
        });
    }

    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;

class BlogPost extends Model
{
    use SoftDeletes;
    // //
    //retationship with blogpost to user
    public function user(){
        return $this->belongsTo('App\User');
    }
     protected $table = 'blogposts';
        protected $fillable = [
            'title','content','user_id'
        ];
    public function comments(){
        return $this->hasMany('App\Comment')->latest();
    }

    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT,'desc');
    }
    public function scopeMostCommented(Builder $query){
        return $query->withCount('comments')->orderBy('comments_count','desc');
    }

    public static function boot(){
         parent::boot();
        static::deleting(function (BlogPost $post){
            $post->comments()->delete();
        });
        // static::addGlobalScope(new LatestScope);
        static::restoring(function(BlogPost $post){
            $post->comments()->restore();
        });
    }
}

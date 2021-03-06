<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes;
    // //
    //retationship with blogpost to user
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function tags(){
        // return $this->belongsToMany('App\Tag')->withTimeStamps();
        return $this->belongsToMany('App\Tag')->withTimeStamps();
    }

    //  protected $table = 'blogposts';
        protected $fillable = [
            'title','content','user_id'
        ];
    public function comments(){
        return $this->hasMany('App\Comment')->latest();
    }

    public function image(){
        return $this->morphOne('App\Image','imageable');
    }

    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT,'desc');
    }
    public function scopeMostCommented(Builder $query){
        return $query->withCount('comments')->orderBy('comments_count','desc');
    }

    public function scopeLatestWithRelations(Builder $query){
        return $query->latest()
        ->withCount('comments')
        ->with('user')
        ->with('tags');
    }

    public static function boot(){
        static::addGlobalScope(new DeletedAdminScope);
         parent::boot();
        static::deleting(function (BlogPost $post){
            $post->comments()->delete();
            Cache::tags(['blog-post'])->forget('blog-post-{$post->id}');

        });
        // static::addGlobalScope(new LatestScope);
        static::restoring(function(BlogPost $post){
            $post->comments()->restore();
        });
        static::updating(function(BlogPost $post){
            Cache::tags(['blog-post'])->forget('blog-post-{post->id}');
        });
    }
}

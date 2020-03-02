<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use App\Traits\Taggable;

class BlogPost extends Model
{
    use SoftDeletes, Taggable;
    // //
    //retationship with blogpost to user
    public function user(){
        return $this->belongsTo('App\User');
    }

    // public function tags(){
        //not used// replace with traits
    //     // return $this->belongsToMany('App\Tag')->withTimeStamps();
    //     return $this->morphToMany('App\Tag','taggable')->withTimeStamps();
    // }

    //  protected $table = 'blogposts';
        protected $fillable = [
            'title','content','user_id'
        ];
    public function comments(){
        return $this->morphMany('App\Comment', 'commentable')->latest();
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
        
    }
}

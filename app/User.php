<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //relation with post with user
    public function blogposts(){
        return $this->hasMany(BlogPost::class);
    }

    public function commentsOn()
    {
        return $this->morphMany('App\Comment', 'commentable')->latest();
    }
    //relation with comments with user
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    //polymorphic relation with user to image
    public function image(){
        return $this->morphOne('App\Image','imageable');
    }

    public function scopeWithMostBlogPosts(Builder $query){
        $query->withCount('blogposts')->orderBy('blogposts_count', 'desc');
    }

    public function scopeWithMostActivityLastMonth(Builder $query){
        return $query->withCount(['blogposts' => function(Builder $query){
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(1),now()]);
        }])
        ->has('blogposts','>=',2)
        ->orderBy('blogposts_count','desc');
    }
    public function scopeThatHasCommentedOnPost(Builder $query,BlogPost $post){
       return $query->whereHas('comments', function($query) use($post){
            return $query->where('commentable_id','=',$post->id)
                    ->where('commentable_type','=','App\BlogPost');
        });
    }

    public function scopeThatIsAnAdmin(Builder $query){
        return $query->where('is_admin',true);
    }

}

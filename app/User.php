<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\BlogPost;

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
}

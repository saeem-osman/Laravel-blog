<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LatestScope;



class Comment extends Model
{
    //
    use SoftDeletes;
    public function blogPost(){
        return $this->belongsTo('App\BlogPost');
    }

    public static function boot(){
        parent::boot();
        // static::addGlobalScope(new LatestScope);
    }

    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}

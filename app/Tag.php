<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    public function blogposts(){
        // return $this->belongsToMany('App\BlogPost')->withTimeStamps();
        return $this->morphedByMany('App\BlogPost','taggable')->withTimeStamps()->as('tagged');

    }
    public function comments(){
        return $this->morphedByMany('App\Comments','taggable')->withTimeStamps()->as('tagged');
    }
}

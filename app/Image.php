<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path'];

    // public function blogposts(){
    //     return $this->belongsTo('App\BlogPost');
    // }
    public function imageable(){
        return $this->morphTo();
    }
    public function url(){
        return Storage::url($this->path);
    }
}

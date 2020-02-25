<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\BlogPost;

class PostTagController extends Controller
{
    //
    public function index($tag){
        $tag = Tag::findOrFail($tag);
        return view('posts.index', [
            'posts'=> $tag->blogposts()
                          ->latestWithRelations()
                          ->get()
            
            ]);
    }
}

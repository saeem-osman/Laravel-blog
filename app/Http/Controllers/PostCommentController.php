<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use App\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;

class PostCommentController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store']);
    }
    //
    public function store(BlogPost $post, StoreComment $request){
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        Mail::to($post->user)->send(
            // new CommentPosted($comment)
            new CommentPostedMarkdown($comment)
        );

        return redirect()->back()->withStatus('Comment was created!');

    }
}

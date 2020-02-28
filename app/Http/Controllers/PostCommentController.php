<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use App\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;

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

        // Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );
        
        // Mail::to($post->user)->queue(
        //     new CommentPostedMarkdown($comment)
        // );

        ThrottledMail::dispatch(new CommentPostedMarkdown($comment), $post->user)
        ->onQueue('low');

        // $when = now()->addMinutes(1);
        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );

        NotifyUsersPostWasCommented::dispatch($comment)
        ->onQueue('high');

        return redirect()->back()->withStatus('Comment was created!');

    }
}

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use App\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentPosted as oldCommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;
use App\Events\CommentPosted;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store','delete']);
    }
    //

    public function index(BlogPost $post){
        // dump(get_class($post->comments)); 
        // dump(is_array($post->comments));
        // die;
        return CommentResource::collection($post->comments()->with('user')->get());
        // return new CommentResource($post->comments->first());
        // return $post->comments()->with('user')->with('tags')->get();
    }
    public function store(BlogPost $post, StoreComment $request){
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        event(new CommentPosted($comment));
        

        return redirect()->back()->withStatus('Comment was created!');

    }
}
// previous section for reference
// Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );
        
        // Mail::to($post->user)->queue(
        //     new CommentPostedMarkdown($comment)
        // );
        // $when = now()->addMinutes(1);
        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );
        
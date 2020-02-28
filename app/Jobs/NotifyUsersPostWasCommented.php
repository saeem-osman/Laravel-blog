<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CommentPostedOnPostWatch; 
use Illuminate\Support\Facades\Mail;
use App\Comment;
use App\User;
class NotifyUsersPostWasCommented
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::thatHasCommentedOnPost($this->comment->commentable)
                ->get()
                ->filter(function(User $user){
                    return $user->id !== $this->comment->user_id;
                })->map(function(User $user){
                    ThrottledMail::dispatch(
                        new CommentPostedOnPostWatch($this->comment, $user),
                        $user
                    );
                });
    }
}

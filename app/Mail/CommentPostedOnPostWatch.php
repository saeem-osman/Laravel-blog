<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Comment;
use App\User;

class CommentPostedOnPostWatch extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $comment, $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@batman.com','batman')
                    ->markdown('emails.posts.comment-posted-on-watch');
    }
}

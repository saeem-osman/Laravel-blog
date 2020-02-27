<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Comment;

class CommentPostedMarkdown extends Mailable
{
    use Queueable, SerializesModels;
    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Comment was posted on your <<{$this->comment->commentable->title} >> blogpost.";
        return $this->subject($subject)
            ->from('admin@lara.com','admin')
                ->markdown('emails.posts.commented-markdown');
    }
}

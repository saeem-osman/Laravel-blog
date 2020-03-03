<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CommentPosted;
use App\Jobs\ThrottledMail;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Mail\CommentPostedMarkdown;

class NotifyUsersAboutComment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        // dd($event->comment->content);
        ThrottledMail::dispatch(new CommentPostedMarkdown($event->comment),
         $event->comment->commentable->user)
        ->onQueue('low');

        NotifyUsersPostWasCommented::dispatch($event->comment)
        ->onQueue('high');
    }
}

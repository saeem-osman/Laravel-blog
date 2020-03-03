<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BlogPostPosted;
use App\Mail\BlogPostAdded;
use App\User;
use App\Jobs\ThrottledMail;


class NotifyAdminWhenBlogPostCreated
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
    public function handle(BlogPostPosted $event)
    {
        // dd($event->post->content);
        // dd(User::ThatIsAnAdmin()->get());
        User::ThatIsAnAdmin()->get()->map(
            function(User $user){
                ThrottledMail::dispatch(
                    new BlogPostAdded(),
                    $user
                )->onQueue('high');
            }
        );
    }
}

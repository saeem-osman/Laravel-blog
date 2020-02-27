<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Comment;
use Illuminate\Support\Facades\Storage;
class CommentPosted extends Mailable
{
    public $comment;
    use Queueable, SerializesModels;

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
            ->from('admin@laravelmighty.com','Admin')
                // first example with file attachment
                // ->attach(
                //     storage_path('app/public') . '/' . $this->comment->user->image->path,
                //     [
                //         'as' => 'profile_picture.jpg',
                //         'mime' => 'image/jpeg'
                //     ]
                // )
                // ->attachFromStorage($this->comment->user->image->path, 'profile_image.jpg')
                // ->attachFromStorageDisk('public', $this->comment->user->image->path,'profile.jpg')
                ->attachData(Storage::get($this->comment->user->image->path), 'profilefromdata.jpg',[
                    'mime' => 'image/jpeg'
                ])
                    ->view('emails.posts.commented');
    }
}

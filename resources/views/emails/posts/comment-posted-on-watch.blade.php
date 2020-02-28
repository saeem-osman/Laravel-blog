@component('mail::message')
# Comment was posted on post that you are watching
Hi!! {{$comment->commentable->user->name }}
@component('mail::button', ['url' => route('posts.show',['post' => $comment->commentable->id])])
View Blogpost
@endcomponent

@component('mail::button', ['url' => route('users.show', ['user' => $comment->user->id])])
View {{ $comment->user->name }} Profile
@endcomponent

@component('mail::panel')
{{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

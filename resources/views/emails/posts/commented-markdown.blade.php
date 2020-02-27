@component('mail::message')
# Comment was posted on your blogpost
Hi!! {{$comment->commentable->user->name }}

Someone has commented on your blogpost

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

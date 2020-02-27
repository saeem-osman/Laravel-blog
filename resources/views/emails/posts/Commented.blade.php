<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<p>Hi!! {{$comment->commentable->user->name }} </p>

<p>Someone has commented on your blogpost
<a href="{{route('posts.show',['post' => $comment->commentable->id])}}">{{ $comment->commentable->title }}</a>
</p>
<hr />
<p>
    <a href="{{route('users.show', ['user' => $comment->user->id])}}">{{ $comment->user->name }}</a>
    <img src="{{ $message->embed($comment->user->image->url() ) }}" />
    has said: 
</p>
<p> {{ $comment->content }}</p>
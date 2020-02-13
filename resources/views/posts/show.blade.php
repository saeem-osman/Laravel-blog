@extends('layout')
@section('content')
    <h1>Individual Blog post</h1>
    <h3>{{$post->title}}</h3>
    <p>{{$post->content}}</p>
    <p>Added on: {{$post->created_at->diffForHumans()}}</p>
    @if ((new Carbon\Carbon())->diffInMinutes($post->created_at)<5)
        New Post!!!
    @endif
    <h4 class="lead">Comments</h4>
    @forelse ($post->comments as $comment)
    <p>{{$comment->content}}</p>
    <p class="text-muted">created at {{$comment->created_at->diffForHumans()}}</p>
    @empty
        <p>No comment yet!!!</p>
    @endforelse
@endsection
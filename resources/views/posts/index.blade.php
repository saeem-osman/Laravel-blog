@extends('layout')
@section('content')
    <h1>This is posts main page.</h1>
    @forelse ($posts as $post)
        <h3><a href="{{route('posts.show',['post'=>$post->id])}}">{{$post->title}}</a></h3>
        @if ($post->comments_count)
            <p>{{$post->comments_count}} comments </p>
        @else
            <p>No comments yet </p>
        @endif
        <a class="btn btn-primary" href="{{route('posts.edit',['post'=>$post->id])}}">Edit</a>
        <form class="fm-line"
         method="POST" action="{{route('posts.destroy',['post'=>$post->id])}}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">Delete</button>
        </form>
        <hr>
    @empty
        <p>Nothing to show here</p>
    @endforelse
    

@endsection
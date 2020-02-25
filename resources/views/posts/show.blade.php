@extends('layout')
@section('content')
<div class="row">
    <div class="col-8">
        <h3>{{$post->title}}
        @badge(['show' => now()->diffInMinutes($post->created_at)<30, 'type' => 'primary'])
            Brand New Post
        @endbadge
        </h3>
        <p>{{$post->content}}</p>
        @updated(['date'=>$post->created_at, 'name'=>$post->user->name])
        @endupdated

        @updated(['date'=>$post->updated_at])
            Updated
        @endupdated
        <p class="lead">Currently read by {{$counter}} people. </p>
        @tags([ 'tags'=> $post->tags ])  @endtags
        <h4 class="lead">Comments</h4>
        @include('comments._form')
        @forelse ($post->comments as $comment)
        <p>{{$comment->content}}</p>
        @updated(['date'=>$comment->created_at, 'name'=>$comment->user->name])
            created at
        @endupdated
        @empty
            <p>No comment yet!!!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts._activity')
    </div>
@endsection
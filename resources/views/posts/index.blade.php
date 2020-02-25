@extends('layout')
@section('content')
    <div class="row">
    <div class="col-8">
    @forelse ($posts as $post)
        @if($post->trashed())
        <del>
        @endif
        <h3><a class="{{$post->trashed() ? 'text-muted': ''}}" href="{{route('posts.show',['post'=>$post->id])}}">{{$post->title}}</a></h3>
        @if($post->trashed())
        </del>
        @endif
        @if ($post->comments_count)
            <p>{{$post->comments_count}} comments </p>
        @else
            <p>No comments yet </p>
        @endif
        @updated(['date'=>$post->created_at, 'name'=>$post->user->name])

        @endupdated
        @tags([ 'tags'=> $post->tags ])  @endtags
        @auth
            @can('update', $post)
            <a class="btn btn-primary" href="{{route('posts.edit',['post'=>$post->id])}}">
                Edit
            </a> 
            @endcan
        @endauth
        @if (!$post->trashed())
        @auth
            @can('delete', $post)
            <form class="fm-line"
            method="POST" action="{{route('posts.destroy',['post'=>$post->id])}}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">Delete</button>
        </form>
        @endcan
        @endauth      
        @endif
        <hr>
    @empty
        <p>Nothing to show here</p>
    @endforelse
    </div>
    <div class="col-4">
        @include('posts._activity')
    </div>

@endsection
    
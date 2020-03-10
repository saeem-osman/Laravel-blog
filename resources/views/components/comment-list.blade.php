@forelse ($comments as $comment)
        <p>{{$comment->content}}</p>
        @updated(['date'=>$comment->created_at, 'name'=>$comment->user->name, 'userId' => $comment->user->id])
            created at
        @endupdated
        @tags([ 'tags'=> $comment->tags ])  @endtags

        @empty
            <p>No comment yet!!!</p>
@endforelse
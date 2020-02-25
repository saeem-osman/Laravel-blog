<div class="container">
    <div class="row">
        @card(['title'=>'Most Commented'])
            @slot('subtitle')
            What people are interested about
            @endslot
            @slot('items')
                @forelse ($most_commented as $post)
                <li class="list-group-item"><a href=" {{route('posts.show',['post'=>$post->id])}}"> {{$post->title}}</a></li>
            @empty
                <li>such post found</li>
            @endforelse
            @endslot
        @endcard
    
</div>

<div class="row">
    
    @card(['title'=>'Most Active'])
        @slot('subtitle')
            Users that are most active
        @endslot
        @slot('items', collect($most_active)->pluck('name'))
      @endcard
</div>
<div class="row">
        {{-- <div class="card mt-4" style="width: 100%">
            <div class="card-body">
              <h4 class="card-title">Last Month Activity</h4>
              <h6 class="card-subtitle mb-2 text-muted">Most active user last month</h6>
              <ul class="list-group list-group-flush">
              @forelse ($last_month_user as $user)
                <li class="list-group-item">{{$user->name}}</li>
              @empty
                  <li>no such user found</li>
              @endforelse
              </ul>
            
          </div>
    </div> --}}
    @card(['title'=>'Last Month Activity'])
        @slot('subtitle')
        Most active user last month
        @endslot
        @slot('items', collect($last_month_user)->pluck('name'))
      @endcard
</div>
</div>
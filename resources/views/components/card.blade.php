<div class="card mt-4" style="width: 100%">
    <div class="card-body">
    <h4 class="card-title">{{$title}}</h4>
        <h6 class="card-subtitle mb-2 text-muted">{{$subtitle}}</h6>
      <ul class="list-group list-group-flush">
      @if (is_a($items,'Illuminate\Support\Collection'))
        @forelse ($items as $item)
        <li class="list-group-item">
            {{$item}}
        </li>
        @empty
            <li>such post found</li>
        @endforelse
      @else
        {{$items}}
      @endif
      </ul>
    </div>
  </div>
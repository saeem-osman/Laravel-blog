<div class="form-group">
    <label>Title</label>
    <input class="form-control" type="text" name="title" value="{{old('title',$post->title ?? null)}}" />
<div>
<div class="form-group">
    <label>Content</label>
    <textarea class="form-control" name="content">{{old('content',$post->content ?? null)}}</textarea>
</div>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li><p class=""> {{$error}}</p></li>
            @endforeach
        </ul>
    </div>    
@endif
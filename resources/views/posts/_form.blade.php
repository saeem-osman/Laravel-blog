<div class="form-group">
    <label>Title</label>
    <input class="form-control" type="text" name="title" value="{{old('title',$post->title ?? null)}}" />
<div>
<div class="form-group">
    <label>Content</label>
    <textarea class="form-control" name="content">{{old('content',$post->content ?? null)}}</textarea>
</div>
<div class="form-group">
    <label>File Thumbnail</label>
    <input type="file" class="form-control-file" name="thumbnail">
</div>
@errors 
@enderrors
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\User;
use App\Image;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Events\BlogPostPosted;
use App\Facades\CounterFacade;

class PostController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth')
        ->only(['create','edit','store','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index',[
            'posts'=> BlogPost::latestWithRelations()->get()
            ]);
        // return BlogPost::latestWithRelations()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validateData = $request->validated();
        $validateData['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validateData);

        if($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnails');
            $blogPost->image()->save(
                Image::make(['path' => $path])
            );

            




            //***** File upload in laravel */
            // dump($file->getClientMimeType());
            // dump($file->getClientOriginalExtension());
            // dump($file->store('thumbnails'));
            // dump(Storage::disk('public')->putFile('thumbnails',$file));
            // $name1 = dump($file->storeAs('thumbnails', $blogPost->id . '.' . $file->guessExtension()));
            // $name2 = dump(Storage::disk('local')->putFileAs('thumbnails',$file,$blogPost->id . '.' . $file->guessExtension()));
            // dump(Storage::url($name1));
            // dump(Storage::disk('local')->url($name2));
        }
        // $blogPost->title = $request->input('title');
        // $blogPost->content = $request->input('content');
        // $blogPost->save();

        event(new BlogPostPosted($blogPost));

        $request->session()->flash('status','A new blog post has been created');
        return redirect()->route('posts.show',['post'=>$blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    //    // dd(BlogPost::find($id));
        //$request->session()->reflash();
        // return view('posts.show',['post'=> BlogPost::with(['comments'=> function($query){
        //     return $query->latest();
        // }])->findOrFail($id)]);
        
        //checking the currently active user on our blogpost
        $blogPost = Cache::tags(['blog-posts'])->remember('blog-post-{$id}',60, function() use($id){
            // return BlogPost::with('comments')
            //                 ->with('tags')
            //                 ->with('user')
            //                 ->with('comments.user')
            //                 ->findOrFail($id);
            //same as below
                return BlogPost::with('comments','tags','user','comments.user')->findOrFail($id);
        });
        // $counter = resolve(Counter::class);
        // dd($this->counter);
        return view('posts.show',[
            'post'=> $blogPost,
            'counter'=> CounterFacade::increments("blog-post-{$id}", ['blog-post']),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = BlogPost::findOrFail($id);
        // if(Gate::denies('posts.update', $post)){
        //     abort(403, 'You are not able to update this post.');
        // }
        // $this->authorize('update',$post);
        // the upper line is similar to the line below
        $this->authorize($post);
        return view('posts.edit',['post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize('update',$post);
        // $this->authorize('delete-post',$post);
        // if(Gate::denies('posts.update', $post)){
        //     abort(403, 'You are not able to update this post.');
        // }
        $validateData = $request->validated();
        $post->fill($validateData);
        if($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnails');
            if($post->image){
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }else{
                $post->image()->save(
                    Image::make(['path' => $path])
                );
            
            }
        }
        $post->save();
        $request->session()->flash('status','Post has been updated');
        return redirect()->route('posts.show',['post'=>$post->id]);
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        
        $post = BlogPost::findOrFail($id);
        $this->authorize('posts.delete',$post);
        // if(Gate::denies('delete-post', $post)){
        //     abort(403, 'You are not able to delete this post.');
        // }
        // $this->authorize('posts.delete',$post);
        $post->delete();

        $request->session()->flash('status','Post has been Deleted');
        return redirect()->route('posts.index');

    }
}

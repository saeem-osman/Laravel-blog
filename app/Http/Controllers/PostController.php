<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


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
        //
        // dd(BlogPost::all());
        // DB::connection()->enableQueryLog();
        // $posts = BlogPost::all();
        // $posts = BlogPost::with('comments')->get();
        // foreach($posts as $post){
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }
        // DD(DB::getQueryLog());
        //comment_count
        return view('posts.index',['posts'=> BlogPost::withCount('comments')->get()]);
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
        // $blogPost->title = $request->input('title');
        // $blogPost->content = $request->input('content');
        // $blogPost->save();

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
        return view('posts.show',['post'=> BlogPost::with('comments')->findOrFail($id)]);
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
        //
        $post = BlogPost::findOrFail($id);
        // if(Gate::denies('delete-post', $post)){
        //     abort(403, 'You are not able to delete this post.');
        // }
        // $this->authorize('posts.delete',$post);
        $this->authorize($post);
        $post->delete();

        $request->session()->flash('status','Post has been Deleted');
        return redirect()->route('posts.index');

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\User;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

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
        
        //checking the currently active user on our blogpost
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $userKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-posts'])->get($userKey, []);
        $userUpdate = [];
        $difference = 0;
        $now = now();

        foreach($users as $session => $lastVisit){
            if($now->diffInMinutes($lastVisit) >= 1){
                $difference--;
            }else{
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(!array_key_exists($sessionId,$users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ){
            $difference++;
        }
        
        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-posts'])->forever($userKey, $userUpdate);
        
        if(!Cache::tags(['blog-posts'])->has($counterKey)){
            Cache::tags(['blog-posts'])->forever($counterKey,1);
        }else{
            Cache::tags(['blog-posts'])->increment($counterKey, $difference);
        }

        $counter = Cache::tags(['blog-posts'])->get($counterKey);

        $blogPost = Cache::tags(['blog-posts'])->remember('blog-post-{$id}',60, function() use($id){
            // return BlogPost::with('comments')
            //                 ->with('tags')
            //                 ->with('user')
            //                 ->with('comments.user')
            //                 ->findOrFail($id);
            //same as below
                return BlogPost::with('comments','tags','user','comments.user')->findOrFail($id);
        });
        return view('posts.show',[
            'post'=> $blogPost,
            'counter'=> $counter
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

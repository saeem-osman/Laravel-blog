<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//another system for using rout

// Route::get('/', function () {
//     return view('home');
// });
// Route::get('/about',function(){
//     return view('about');
// });

// Route::view('/','home')->name('home');
// Route::view('/about','about')->name('about');
Route::get('/','HomeController@home')->name('home')
// ->middleware('auth')
;
Route::get('/secret', 'HomeController@secret')
    ->name('secret')
    ->middleware('can:home.secret');

Route::get('/about','HomeController@about')->name('about');
// Route::resource('/posts','PostController')->only(['index','show','create','store']);
// Route::resource('/posts','PostController')->only(['index','show','create','store','edit','update']);
// Route::resource('/posts','PostController')->except(['destroy']);
Route::resource('posts','PostController');
Route::resource('posts.comments', 'PostCommentController')->only(['index','store']);
Route::resource('users.comments', 'UserCommentController')->only(['store']);
Route::get('/posts/tags/{tag}','PostTagController@index')->name('posts.tags.index');
Auth::routes();

Route::get('mailable', function(){
    $comment = App\Comment::find(1);
    return new App\Mail\CommentPostedMarkdown($comment);
});

//user
Route::resource('users', 'UserController')->only(['show','edit','update']);

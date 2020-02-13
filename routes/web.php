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
Route::get('/about','HomeController@about')->name('about');
// Route::resource('/posts','PostController')->only(['index','show','create','store']);
// Route::resource('/posts','PostController')->only(['index','show','create','store','edit','update']);
// Route::resource('/posts','PostController')->except(['destroy']);
Route::resource('/posts','PostController');
Auth::routes();

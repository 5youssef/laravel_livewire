<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('welcome');
});

route::get('/secret', 'HomeController@secret')->name('secret')->middleware('can:secret');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::patch('/posts/{id}/restore', 'PostController@restore')->name('posts.restore');
Route::delete('/posts/{id}/forcedelete', 'PostController@forcedelete')->name('posts.forceDelete');
Route::get('/posts/archive', 'PostController@archive')->name('posts.archive');
Route::get('/posts/all', 'PostController@all')->name('posts.all');
Route::resource('posts', 'PostController');
Route::resource('posts.comments', 'PostCommentController')->only('store');
Route::resource('users.comments', 'UserCommentController')->only('store');

Route::get('/posts/tag/{tag}', 'PostTagController@index')->name('posts.tag.index');


Route::resource('users', 'UserController')->only(['show', 'edit', 'update']);
Route::get('/search', function () {
    return view('posts.index');
});

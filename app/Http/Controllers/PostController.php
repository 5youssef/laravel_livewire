<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use App\Http\Livewire\Filter;


class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'all', 'archive']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(
            'posts.index'
        );
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $post = Post::create($request->all());
        //Upload picture for current post
        $hasFile = $request->hasFile('picture');
        if($hasFile)
        {
            $path = $request->file('picture')->store('posts');
            $image = new Image(['path' => $path]);
            $post->image()->save($image);
            // $post->image()->save(Image::make(['path' => $path]));
        }
        $request->session()->flash('status', 'Blog post was created!');
        return redirect()->route('posts.show', ['post' => $post->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $ttl = 60;
        $postShow = Cache::remember("post-show-{$post->id}", $ttl, function() use($post) {
            return Post::with(['comments', 'comments.user', 'tags', 'image'])->findOrFail($post->id); // eager # leaze  //  comments.user => nested ( relationships/ query)
        });

        return view('posts.show', [
            'post' => $postShow
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->authorize("update", $post);

        return view(
            'posts.edit',
            [
                'post' => $post,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, Post $post)
    {
        // if(Gate::denies('post.update', $post)){
        //     abort(403, 'You can\'t edit this post');
        // }
        $this->authorize('update', $post);
        $post->update($request->all());

        // Upload Picture for current Post
        $hasFile = $request->hasFile('picture');
        if($hasFile)
        {
            $path = $request->file('picture')->store('posts');

                if($post->image) {
                  Storage::delete($post->image->path);
                  $post->image->path = $path;
                  $post->image->save();
                }
                else {
                    $post->image()->save(Image::make(['path' => $path]));
                }
        }

        $request->session()->flash('status', 'Blog post was updated!');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Request $request)
    {
        $this->authorize('delete', $post);
        $post->delete();
        // Post::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive()
    {

        return view(
            'posts.index',
            [
                'posts' => Post::onlyTrashed()->withCount('comments')->get(),
                'tab'   => 'archive'
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function all()
    {

        return view(
            'posts.index',
            [
                'posts' => Post::withTrashed()->withCount('comments')->get(),
                'tab'   => 'all'
            ]
        );
    }


    public function restore($id) {
        $post = Post::onlyTrashed()->where('id', $id)->first();

        $this->authorize('restore', $post);
        $post->restore();
        return redirect()->back();
    }

    public function forcedelete($id) {
        $post = Post::onlyTrashed()->where('id', $id)->first();

        $this->authorize('forceDelete', $post);

        $post->forceDelete();
        return redirect()->back();
    }

}

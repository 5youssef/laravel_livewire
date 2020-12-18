<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Mail\CommentPosted;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    //function create =>  create massive  we have to add $fillabe in model
    public function store(StoreComment $request, Post $post)
    {
        $comment = $post->comments()->create($request->all());
        Mail::to($post->user->email)->send(new CommentPosted($comment));
        return redirect()->back()->withStatus('Comment was created');
    }
}

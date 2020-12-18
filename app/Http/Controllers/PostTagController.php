<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    public function index(Tag $tag)
    {
        return view('posts.index',
            [
                'posts' => $tag->posts()->postWithUserCommentsTagsImage()->get(),
            ]
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;
use Illuminate\Http\Request;

class UserCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    //function create =>  create massive  we have to add $fillabe in model
    public function store(StoreComment $request, User $user)
    {
        $user->comments()->create($request->all());
        return redirect()->back()->withStatus('Comment was created');
    }
}

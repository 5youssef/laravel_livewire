@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-8">
        <h1>{{ $post->title }}</h1>
        @if($post->image)
        <img src="{{ $post->image->url() }}" class="mt-3 img-fluid rounded" alt="">
        @endif
        <p>{{ $post->content }}</p>

        <p>
            <x-tags :tags="$post->tags"></x-tags>
        </p>

        {{-- <p  class="text-muted">Added {{ $post->created_at->diffForHumans() }}</p> --}}

        <x-updated :date="$post->created_at" :name="$post->user->name" :user-id="$post->user->id" />

            @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5 )
            <strong>New!</strong>
            @endif


            {{-- @include('comments.form', ['id' => $post->id]) --}}
            <x-comment-form :action="route('posts.comments.store', ['post' => $post->id])"/>

            <hr/>

            <x-comment-list :comments="$post->comments" />

        </div>
        <div class="col-4">
            @include('posts.sidebar')
        </div>
    </div>

    @endsection('content')

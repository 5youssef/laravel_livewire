<div class="col-8">
    <div class="row">
        <div class="col-3">

            <h3>Find a post</h3>

        </div>
        <div class="col-9">
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input type="text"  wire:model="searchTerm" class="form-control" placeholder="Search">
            </div>
        </div>
    </div>
    <div class="my-3">
        <h4>{{ $count_posts }} Post(s)</h4>
    </div>

    @forelse ($posts as $post)
    <p>

        @if($post->created_at->diffInHours() < 1)
        <x-badge color="success">New</x-badge>
        @else
        <x-badge color="dark">Old</x-badge>
        @endif

        <h3>
            <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                @if($post->trashed())
                <del>
                    {{ $post->title }}
                </del>
                @else
                {{ $post->title }}
                @endif
            </a>
        </h3>

        @if($post->image)
        <img src="{{ $post->image->url() }}" class="mt-3 img-fluid rounded" alt="">
        @endif

        <p>
            <x-tags :tags="$post->tags"></x-tags>
        </p>

        {{-- <a href="http://"><i class="far fa-thumbs-up" aria-hidden="true"></i></a>
        <a href="http://"><i class="fa fa-thumbs-down" aria-hidden="true"></i></a> --}}

        <button class="btn btn-info btn-sm" wire:click="like({{ $post->id }})">Like Post</button>
        <button class="btn btn-info btn-sm" wire:click="dislike">Dislike Post</button>


        <x-comment-form :action="route('posts.comments.store', ['post' => $post->id])" />


            @if($post->comments_count)
            <p>{{ $post->comments_count }} comments</p>
            @else
            <p>No comments yet!</p>
            @endif

            <x-updated :date="$post->created_at" :name="$post->user->name" :user-id="$post->user->id"></x-updated>
            <x-updated :date="$post->updated_at">Updated</x-updated>

            @auth
            @can('update', $post)
            <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                class="btn btn-primary btn-sm">
                Edit
            </a>
            @endcan


            @if (!$post->deleted_at)
            @can('delete', $post)

            <form method="POST" class="fm-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                @csrf
                @method('DELETE')

                <input type="submit" value="Delete!" class="btn btn-sm btn-dark"/>
            </form>
            @endcan
            @else
            @can('restore', $post)

            <form method="POST" class="fm-inline" action="{{ route('posts.restore', $post->id) }}">
                @csrf
                @method('PATCH')

                <input type="submit" value="Restore!" class="btn btn-sm btn-success"/>
            </form>
            @endcan
            @can('forceDelete', $post)
            <form method="POST" class="fm-inline" action="{{ route('posts.forceDelete', $post->id) }}">
                @csrf
                @method('DELETE')

                <input type="submit" value="Force delete!" class="btn btn-sm btn-danger"/>
            </form>
            @endcan
            @endif
            @endauth


            <hr>
        </p>
        @empty
        <p>No blog posts yet!</p>
        @endforelse
        {{ $posts->links('livewire.livewire-pagination') }}
    </div>

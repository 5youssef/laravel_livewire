
<h6>{{ empty($tags->all()) ? '' : 'TAGS' }}</h6>
@foreach ($tags as $tag)
<a href="{{ route('posts.tag.index', $tag->id) }}">
    <span class="badge badge-success">{{ $tag->name }}</span>
</a>
@endforeach

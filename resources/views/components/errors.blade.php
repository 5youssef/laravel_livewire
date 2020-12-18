{{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif --}}

    @if($errors->has($name))
    <div class="alert alert-danger">
        {{ $errors->first($name) }}
    </div>
    @endif

@extends('layouts.app')

@section('content')

<form action="{{ route('users.update', ['user' => $user->id ]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="co-md-4">
            <h5>Avatar user</h5>
            <img src="{{ $user->image ? $user->image->url() : '' }}" alt="" class="img-thumbnail avatar">
            <input type="file" name="avatar" id="avatar" class="form-control-file">
            <x-errors name="avatar"/>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary form-control">Update</button>
        </div>
    </div>
</form>

@endsection

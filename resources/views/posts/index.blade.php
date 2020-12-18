@extends('layouts.app')

@section('content')
<div class="row">
    @livewire('filter')


    <div class="col-4">
        @include('posts.sidebar')
    </div>
</div>

@endsection('content')

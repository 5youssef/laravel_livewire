@auth
<h5>Add comment</h5>
<form method="POST" action="{{ $action }}">
    @csrf
    <textarea class="form-control my-3" name="content" id="content"  rows="3"></textarea>
    <x-errors name="content" />

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Create!</button>
    </div>
</form>
@else
<a href="{{ route('login') }}" class="btn btn-success btn-sm my-3">Sing In</a> to post a comment !
@endauth


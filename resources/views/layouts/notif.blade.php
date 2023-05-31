@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ $message }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
@extends('base.main')

@section('body')
    <div class="container">
        <h1>Import</h1>

        <h2>Import For <em>{{ $card->name }}</em></h2>

        <form action="/import/{{ $card->id }}" method="post" class="form" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
                <label>Files</label>
                <input type="file"
                       class="form-control"
                       name="files[]"
                       multiple />
            </div>

            <button class="btn btn-lg btn-primary" type="submit">
                Submit
            </button>

        </form>
    </div>
@endsection

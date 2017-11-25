@extends('base.main')

@section('body')
    <div class="container">
        <h1>Categories</h1>

        @foreach($categories as $category)
        <p>
            <a class="btn btn-block btn-default btn-lg" href="/categories/{{ $category->id }}">{{ $category->name }}</a>
        </p>
        @endforeach
    </div>
@endsection

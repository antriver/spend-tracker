@extends('base.main')

@section('body')
    <div class="container">
        <h1>Import</h1>

        <h2>Import For <em>{{ $card->name }}</em></h2>

        @foreach($results as $filename => $result)
            <h3>{{ $filename }}</h3>
            {!! $result !!}
        @endforeach
    </div>
@endsection

@extends('base.main')

@section('body')
    <div class="container">
        <h2>Import</h2>

        <h3>Import For <em>{{ $card->name }}</em></h3>

        @foreach($results as $filename => $result)
            <h4>{{ $filename }}</h4>
            {!! $result !!}
        @endforeach
    </div>
@endsection

@extends('base.main')

@section('body')
    <div class="container">
        <h1>Import</h1>

        <h2>Cards</h2>

        @foreach($cards as $card)
        <p>
            <a class="btn btn-block btn-default btn-lg" href="/import/{{ $card->id }}">{{ $card->name }}</a>
        </p>
        @endforeach
    </div>
@endsection

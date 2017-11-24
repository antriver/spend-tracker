@extends('base.main')

@section('body')
    <div class="container">
        <h2>Import</h2>

        <h3>Cards</h3>
        <ul>
            @foreach($cards as $card)
            <li>
                <a href="/import/{{ $card->id }}">{{ $card->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endsection

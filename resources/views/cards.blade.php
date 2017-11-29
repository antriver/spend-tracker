@extends('base.main')

@section('body')
    <div class="container">
        <h1>Cards</h1>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Card Name</th>
                    <th>Last Transaction</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

            @foreach($cards as $card)
            <tr>
                <td>{{ $card->name }}</td>
                <td>{{ $card->lastTransaction ? $card->lastTransaction->description.' '.$card->lastTransaction->date : '' }}</td>
                <td><a class="btn btn-block btn-default" href="/import/{{ $card->id }}">Import</a></td>
            </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection

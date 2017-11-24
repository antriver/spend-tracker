@extends('base.main')

@section('body')
    <div class="container">
        <h1>Transactions</h1>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Card</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Merchant</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr data-if="{{ $transaction->id }}">
                    <td>{{ $transaction->card->shortName }}</td>
                    <td>{{ $transaction->date->toDateString() }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>&pound;{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->merchant ? $transaction->merchant->name : '' }}</td>
                    <td>
                        @if ($transaction->merchant)

                            {{ $transaction->merchant->category ? $transaction->merchant->category->name : '' }}

                            <select class="form-control"
                                    id="category-selector"
                                    data-merchant-id="{{ $transaction->merchant->id }}">
                                <option>Test</option>
                            </select>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

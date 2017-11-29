@extends('base.main')

@section('body')
    <div class="container">
        <h1>Transactions</h1>

        @if ($category)
            <h2>{{ $category->name }}</h2>
        @endif

        @if ($date)
            <h2>{{ $date }}</h2>
        @endif

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Card</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Merchant</th>
                    <th style="width:200px;">Category</th>
                </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr data-if="{{ $transaction->id }}">
                    <td>{{ $transaction->card->shortName }}</td>
                    <td>{{ $transaction->date->toDateString() }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>&pound;{{ number_format($transaction->amount, 2) }}</td>
                    <td class="merchant-cell" data-merchant-id="{{ $transaction->merchant->id }}">
                        <strong>[{{ $transaction->merchant ? $transaction->merchant->id : '' }}]</strong>
                        <span class="merchant-name">{{ $transaction->merchant ? $transaction->merchant->name : '' }}</span>
                        <a class="btn btn-sm btn-default edit-merchant-name"><i class="glyphicon glyphicon-edit"></i></a>
                    </td>
                    <td>
                        @if ($transaction->merchant)
                            <select class="form-control category-selector"
                                    id="category-selector"
                                    data-merchant-id="{{ $transaction->merchant->id }}">
                                <option <?=(empty($transaction->merchant->categoryId) ? 'selected' : '')?>></option>
                                @foreach ($selectableCategories as $selectableCategory)
                                    <option value="<?=$selectableCategory->id?>" <?=($transaction->merchant->categoryId == $selectableCategory->id ? 'selected' : '')?>>
                                        {{ $selectableCategory->name }} [{{ $selectableCategory->id }}]
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

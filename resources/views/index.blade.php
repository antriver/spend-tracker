@extends('base.main')

@section('body')
    <div class="container-fluid">

        <div class="summary-table-container">
            <table class="table table-striped table-hover summary-table">
                <thead>
                <tr>
                    <th>Month</th>
                    <th>Month Total</th>
                    @foreach ($categories as $category)
                        <th>{{ $category->name }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach ($displayMonths as $month => $monthName)
                    <tr>
                        <td>{{ $monthName }}</td>
                        <td>{{ isset($months[$month]) ? '£'.number_format($months[$month]['total'], 2) : '' }}</td>
                        @foreach ($categories as $category)
                            <td>
                                <a href="/transactions/?category={{ $category->id }}&date={{ $month }}">
                                    {{ !empty($months[$month][$category->id]) ? '£'.number_format($months[$month][$category->id], 2) : '' }}
                                </a>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                @foreach ($averages as $length => $data)
                    <tr>
                        <td>{{ $length }} Month Average</td>
                        <td>{{ '£'.number_format($data['total'], 2) }}</td>
                        @foreach ($categories as $category)
                            <td>
                                {{ !empty($data[$category->id]) ? '£'.number_format($data[$category->id], 2) : '' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                </tfoot>
            </table>
        </div>

    </div>
@endsection

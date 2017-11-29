@extends('base.main')

@section('body')
    <script src="https://unpkg.com/frappe-charts@0.0.7/dist/frappe-charts.min.iife.js"></script>
    <script>
        var categoryPerWeekChartData = <?=json_encode($categoryPerWeekChartData)?>;
    </script>
    <div class="container">
        <h1>Charts</h1>

        <div id="heatmap" style="margin:auto; text-align: center;"></div>
        <script>
            var heatmap = new Chart({
                parent: "#heatmap",
                type: 'heatmap',
                height: 115,
                data: <?=json_encode($amountPerDayChartData)?>,
                discrete_domains: 0,
                legend_colors: ['#ebedf0', '#c6e48b', '#7bc96f', '#239a3b', '#196127'],
            });
        </script>

        <?php /*
        <div id="chart"></div>
        <script>
            var chart = new Chart({
                parent: "#chart",
                title: "Categories Per Week",
                data: categoryPerWeekChartData,
                type: 'line', // or 'line', 'scatter', 'pie', 'percentage'
                height: 500,
            });
        </script>
        */ ?>

        <?php
        foreach ($categoryPerWeekChartData['datasets'] as $i => $dataset) {
        ?>
        <div id="chart-cat-<?=$i?>"></div>
        <script>
            var c<?=$i?> = new Chart({
                parent: "#chart-cat-<?=$i?>",
                title: "<?=$dataset['title']?>",
                data: {
                    labels: categoryPerWeekChartData.labels,
                    datasets: [
                        <?=json_encode($dataset)?>
                    ]
                },
                type: 'bar',
                height: 400,
                heatline: 0,
                is_navigable: 1,
                colors: ['#337ab7']
            });

            c<?=$i?>.parent.addEventListener(
                'data-select',
                function (e) {
                    console.log(e);
                }
            );
        </script>

        <?php
        }
        ?>
    </div>
@endsection

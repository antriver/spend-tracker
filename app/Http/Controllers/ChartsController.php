<?php

namespace SpendTracker\Http\Controllers;

use DateTime;
use DB;
use Illuminate\Http\Request;
use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Category;
use View;

class ChartsController extends AbstractController
{
    public function index(Request $request)
    {
        $period = 'week';
        $sqlDateFormat = '%Y-%V';
        if ($request->get('period') === 'month') {
            $period = 'month';
            $sqlDateFormat = '%Y-%m';
        }

        // Get all categories.
        $categories = Category::orderBy('name')->get();

        $categoryNames = [];
        foreach ($categories as $category) {
            $categoryNames[$category->id] = $category->name;
        }

        // Get earliest date.
        $firstDate = DB::selectOne('SELECT MIN(date) AS `date` FROM transactions');

        // Build array of weeks since then.
        $days = [];
        $weeks = [];
        $months = [];

        //$startDate = new DateTime($firstDate->date);
        $startDate = (new DateTime('-6 MONTHS'))->setTime(0, 0);
        $now = (new DateTime())->setTime(0, 0);
        while ($startDate <= $now) {

            $day = $startDate->format('Y-m-d');
            $week = $startDate->format('Y-W');
            $month = $startDate->format('Y-m');

            $days[] = $day;

            if (!isset($weeks[$week])) {
                $weeks[$week] = $day;
            }

            if (!isset($months[$month])) {
                $months[$month] = $startDate->format('M y');
            }

            $startDate->modify('+1 DAY');
        }

        if ($period === 'week') {
            $categoryPeriodAmounts = array_fill_keys(
                array_keys($categoryNames),
                array_fill_keys(array_keys($weeks), 0.00)
            );
        } else {
            $categoryPeriodAmounts = array_fill_keys(
                array_keys($categoryNames),
                array_fill_keys(array_keys($months), 0.00)
            );
        }

        $categoryPeriodData = DB::select(
            "SELECT c.id, c.name, DATE_FORMAT(`date`,'{$sqlDateFormat}') AS `period`, SUM(t.amount) AS amount
            FROM transactions t
            JOIN merchants m ON m.id = t.merchantId
            JOIN categories c ON c.id = m.categoryId
            WHERE excluded = 0
            GROUP BY DATE_FORMAT(`date`,'{$sqlDateFormat}'), c.id
            ORDER BY `period`"
        );
        foreach ($categoryPeriodData as $row) {
            if ($period === 'week') {
                if (isset($weeks[$row->period])) {
                    $categoryPeriodAmounts[$row->id][$row->period] = floatval($row->amount);
                }
            } else {
                if (isset($months[$row->period])) {
                    $categoryPeriodAmounts[$row->id][$row->period] = floatval($row->amount);
                }
            }
        }
        $parentCategoryPeriodData = DB::select(
            "SELECT p.id, p.name, DATE_FORMAT(`date`,'{$sqlDateFormat}') AS `period`, SUM(t.amount) AS amount
            FROM categories p
            JOIN categories c ON c.parentCategoryId = p.id
            JOIN merchants m ON m.categoryId = c.id
            JOIN transactions t ON t.merchantId = m.id
            WHERE t.excluded = 0
            GROUP BY DATE_FORMAT(`date`,'{$sqlDateFormat}'), p.id
            ORDER BY `period`"
        );
        foreach ($parentCategoryPeriodData as $row) {
            if ($period === 'week') {
                if (isset($weeks[$row->period])) {
                    $categoryPeriodAmounts[$row->id][$row->period] = floatval($row->amount);
                }
            } else {
                if (isset($months[$row->period])) {
                    $categoryPeriodAmounts[$row->id][$row->period] = floatval($row->amount);
                }
            }
        }

        if ($period === 'week') {
            $categoryPerPeriodChartData = [
                'labels' => array_values($weeks),
                'datasets' => []
            ];
        } else {
            $categoryPerPeriodChartData = [
                'labels' => array_values($months),
                'datasets' => []
            ];
        }
        foreach ($categoryPeriodAmounts as $categoryId => $periodAmounts) {
            if (array_sum($periodAmounts) > 0) {
                $categoryPerPeriodChartData['datasets'][] = [
                    'title' => $categoryNames[$categoryId],
                    'values' => array_values($periodAmounts),
                ];
            }
        }

        $amountPerDayData = DB::select(
            "SELECT `date`, SUM(t.amount) AS amount
            FROM transactions t
            WHERE excluded = 0
            GROUP BY `date`
            ORDER BY `date`"
        );
        $amountPerDayChartData = [];
        foreach ($amountPerDayData as $amountPerDay) {
            $dayTimestamp = (new DateTime($amountPerDay->date))->setTime(0, 0)->format('U');
            $amountPerDayChartData[$dayTimestamp] = $amountPerDay->amount;
        }

        return View::make(
            'charts',
            [
                'amountPerDayChartData' => $amountPerDayChartData,
                'categoryPerWeekChartData' => $categoryPerPeriodChartData,
                'period' => $period
            ]
        );
    }
}

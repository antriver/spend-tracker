<?php

namespace SpendTracker\Http\Controllers;

use DateTime;
use DB;
use Illuminate\Http\Request;
use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Category;
use SpendTracker\Models\Transaction;
use View;

class RootController extends AbstractController
{

    /**
     * Display a total of each category per month, and averages per month.
     *
     * @todo calculate month amounts from the last date we have to that date in the previous month.
     */
    public function index()
    {
        $categories = Category::fromQuery(
            'SELECT * FROM categories c ORDER BY c.order DESC, c.name'
        );

        $results = DB::select(
            "SELECT 
                c.id,
                DATE_FORMAT(t.date, '%Y-%m') AS `date`,
                SUM(t.amount) AS amount,
                COUNT(t.id) AS transactions
            FROM categories c
            LEFT JOIN categories childcat ON childcat.parentCategoryId = c.id
            LEFT JOIN merchants m ON (m.categoryId = c.id) OR (childcat.id IS NOT NULL AND m.categoryId = childcat.id)
            JOIN transactions t ON t.merchantId = m.id
            GROUP BY DATE_FORMAT(t.date, '%Y-%m'), c.id
            ORDER BY `date` DESC"
        );

        // Sort by month
        $months = [];
        foreach ($results as $result) {
            $months[$result->date][$result->id] = $result->amount;
        }

        foreach ($months as &$month) {
            // Can't use a simple array_sum here as it would double many amounts due to parent categories.
            $month['total'] = 0.00;
            foreach ($categories as $category) {
                if ($category->selectable && !empty($month[$category->id])) {
                    $month['total'] += $month[$category->id];
                }
            }
        }
        unset($month);

        // Calculate months to display
        $displayMonths = [];

        // Last month with transactions:
        $monthKeys = array_keys($months);
        $endMonth = new DateTime(end($monthKeys).'-01');

        $now = (new DateTime('first day of this month'))->setTime(0, 0, 0);

        while ($now >= $endMonth) {
            $displayMonths[$now->format('Y-m')] = $now->format('M y');
            $now->modify('-1 MONTH');
        }
        $displayMonthKeys = array_keys($displayMonths);

        // Calculate averages
        $averages = [
            2 => [],
            3 => [],
            6 => [],
            12 => []
        ];

        foreach ($averages as $length => &$avgs) {
            reset($displayMonthKeys);
            $sums = [];

            for ($i = 0; $i < $length; $i++) {
                $month = current($displayMonthKeys);
                next($displayMonthKeys);
                if (empty($month)) {
                    break;
                }

                if (!isset($months[$month])) {
                    break;
                }

                $sums['total'][] = $months[$month]['total'];

                foreach ($categories as $category) {
                    if (!empty($months[$month][$category->id])) {
                        $sums[$category->id][] = $months[$month][$category->id];
                    }
                }
            }

            foreach ($sums as $categoryId => $sum) {
                $avgs[$categoryId] = array_sum($sum) / $length;
            }
        }
        unset($avgs);

        return View::make(
            'index',
            [
                'averages' => $averages,
                'categories' => $categories,
                'displayMonths' => $displayMonths,
                'months' => $months,
            ]
        );
    }

    public function transactions(Request $request)
    {
        $category = null;
        $categoryId = $request->get('category') ? intval($request->get('category')) : null;

        $date = $request->get('date') ?: null;

        $selectableCategories = Category::where('selectable', 1)->orderBy('name')->get();

        $transactions = Transaction::with('card', 'merchant', 'merchant.category');

        if ($categoryId) {
            $category = Category::findOrFail($categoryId);

            $categoryIds = [$categoryId];
            foreach ($category->children as $childCategory) {
                $categoryIds[] = $childCategory->id;
            }

            $transactions = $transactions->whereHas(
                'merchant',
                function ($q) use ($categoryIds) {
                    $q->whereIn('categoryId', $categoryIds);
                }
            );
        }

        if ($date) {
            $transactions = $transactions->whereRaw(
                "DATE_FORMAT(transactions.date, '%Y-%m') = ?",
                [
                    $date
                ]
            );
        }

        $transactions = $transactions->orderBy('date', 'DESC')->get();

        return View::make(
            'transactions',
            [
                'category' => $category,
                'date' => $date,
                'selectableCategories' => $selectableCategories,
                'transactions' => $transactions,
            ]
        );
    }

    public function uncategorised()
    {
        $categories = Category::where('selectable', 1)->orderBy('name')->get();
        $transactions = Transaction::with('card', 'merchant', 'merchant.category')
            ->whereHas(
                'merchant',
                function ($q) {
                    $q->whereNull('categoryId');
                }
            )
            ->orderBy('date')->get();

        return View::make(
            'transactions',
            [
                'categories' => $categories,
                'transactions' => $transactions,
            ]
        );
    }
}

<?php

namespace SpendTracker\Http\Controllers;

use DB;
use SpendTracker\Http\Controllers\Base\AbstractController;
use View;

class ChartsController extends AbstractController
{
    public function index()
    {

        $categoriesPerWeekData = DB::select(
            'SELECT WEEK(t.date) as `week`, YEAR(t.date) AS `year`, c.name, SUM(t.amount) AS amount
            FROM transactions t
            JOIN merchants m ON m.id = t.merchantId
            JOIN categories c ON c.id = m.categoryId
            GROUP BY YEAR(t.date), WEEK(t.date), c.id
            ORDER BY `year`, `week`, c.name;'
        );

        print_r($categoriesPerWeekData);
        die();

        return View::make(
            'charts',
            [

            ]
        );
    }
}

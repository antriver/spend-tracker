<?php

namespace SpendTracker\Http\Controllers;

use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Category;
use SpendTracker\Models\Transaction;
use View;

class RootController extends AbstractController
{
    public function index()
    {
        $categories = Category::where('selectable', 1)->orderBy('name')->get();
        $transactions = Transaction::with('card', 'merchant', 'merchant.category')->orderBy('date')->get();

        return View::make(
            'index',
            [
                'categories' => $categories,
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
            'index',
            [
                'categories' => $categories,
                'transactions' => $transactions,
            ]
        );
    }
}

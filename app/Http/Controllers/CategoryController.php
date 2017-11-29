<?php

namespace SpendTracker\Http\Controllers;

use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Category;
use SpendTracker\Models\Transaction;
use View;

class CategoryController extends AbstractController
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return View::make(
            'categories',
            [
                'categories' => $categories,
            ]
        );
    }

    public function show(Category $category)
    {
        $categories = Category::where('selectable', 1)->orderBy('name')->get();
        $transactions = Transaction::with('card', 'merchant', 'merchant.category')
            ->whereHas(
                'merchant',
                function ($q) use ($category) {
                    $q->where('categoryId', $category->id);
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

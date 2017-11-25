<?php

namespace SpendTracker\Http\Controllers\Api;

use Illuminate\Http\Request;
use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Merchant;

class MerchantsController extends AbstractController
{
    public function update(Merchant $merchant, Request $request)
    {
        if ($request->has('categoryId')) {
            $merchant->categoryId = $request->get('categoryId') ?: null;
        }

        if ($request->has('name')) {
            $merchant->name = $request->get('name');
            $merchant->auto = 0;
        }

        $merchant->save();

        return $merchant;
    }
}

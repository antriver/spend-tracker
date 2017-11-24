<?php

namespace SpendTracker\Http\Controllers;

use Illuminate\Http\Request;
use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Merchant;

class ApiController extends AbstractController
{
    public function setCategory(Merchant $merchant, Request $request)
    {
        return \Response::json([]);
    }
}

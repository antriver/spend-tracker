<?php

namespace SpendTracker\Http\Controllers;

use SpendTracker\Http\Controllers\Base\AbstractController;
use SpendTracker\Models\Card;
use SpendTracker\Models\Transaction;
use View;

class CardsController extends AbstractController
{
    public function index()
    {
        $cards = Card::orderBy('name')->get();

        foreach ($cards as $card) {
            $card->lastTransaction = Transaction::where('cardId', $card->id)->orderBy('date', 'DESC')->take(1)->first();
        }

        return View::make(
            'cards',
            [
                'cards' => $cards,
            ]
        );
    }
}

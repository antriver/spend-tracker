<?php

namespace SpendTracker\Libraries\Providers;

use Carbon\Carbon;
use SpendTracker\Models\Transaction;

class MbnaProvider extends AbstractProvider
{
    protected $skipLines = 1;

    protected function createTransactionForLine(string $line): ?Transaction
    {
        $lineData = $this->parseLine($line);

        return new Transaction(
            [
                'date' => Carbon::createFromFormat('d/m/Y', $lineData[0])->setTime(0, 0),
                'amount' => -(floatval($lineData[3])),
                'description' => $lineData[2],
                'raw' => $line
            ]
        );
    }
}

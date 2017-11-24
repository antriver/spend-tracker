<?php

namespace SpendTracker\Libraries\Providers;

use Carbon\Carbon;
use SpendTracker\Models\Transaction;

class AmexProvider extends AbstractProvider
{
    protected function createTransactionForLine(string $line): ?Transaction
    {
        $lineData = $this->parseLine($line);

        return new Transaction(
            [
                'date' => Carbon::createFromFormat('d/m/Y', $lineData[0])->setTime(0, 0),
                'amount' => $lineData[2],
                'description' => $lineData[3],
                'raw' => $line
            ]
        );
    }
}

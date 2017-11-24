<?php

namespace SpendTracker\Libraries\Providers;

use Illuminate\Support\Collection;
use SpendTracker\Models\Card;
use SpendTracker\Models\Transaction;

abstract class AbstractProvider
{
    /**
     * @var string[]
     */
    protected $skipDescriptions = [
        'CREDIT BALANCE REFUNDED',
        'PAYMENT DIRECT DEBIT - THANK YOU',
        'PAYMENT INTERNET DEBIT CARD',
        'PAYMENT RECEIVED - THAN',
        'PAYMENT RECEIVED - THANK YOU',
        'PAYMENT-DEBIT CARD APP',
    ];

    /**
     * @var int
     */
    protected $skipLines = 0;

    /**
     * @param string $line
     *
     * @return null|Transaction
     */
    abstract protected function createTransactionForLine(string $line): ?Transaction;

    /**
     * @param Card $card
     * @param string $fileContents
     *
     * @return Collection|Transaction[]
     */
    public function createTransactionsForFile(Card $card, string $fileContents): Collection
    {
        $lines = $this->parseLines($fileContents);

        $transactions = [];
        foreach ($lines as $i => $line) {
            if ($i < $this->skipLines) {
                continue;
            }
            if (empty($line)) {
                continue;
            }

            if ($transaction = $this->createTransactionForLine($line)) {
                if (in_array($transaction->description, $this->skipDescriptions)) {
                    continue;
                }

                $transaction->cardId = $card->id;

                $transactions[] = $transaction;
            }
        }

        return new Collection($transactions);
    }

    /**
     * @param string $file
     *
     * @return string[]
     */
    protected function parseLines(string $file): array
    {
        return explode("\n", $file);
    }

    protected function parseLine(string $line): array
    {
        return str_getcsv($line);
    }
}

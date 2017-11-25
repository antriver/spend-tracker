<?php

namespace SpendTracker\Libraries;

use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use SpendTracker\Models\Merchant;
use SpendTracker\Models\Transaction;

class TransactionImporter
{
    /**
     * @var Connection
     */
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param Collection|Transaction[] $transactions
     */
    public function import($transactions)
    {
        if (empty($transactions)) {
            return;
        }

        foreach ($transactions as $transaction) {
            $this->info("<b>&pound;{$transaction->amount} @ {$transaction->description}</b>");
            if (empty($transaction->hash)) {
                $this->error('No hash.');
                continue;
            }

            // Find merchant.
            if ($merchant = $this->getMerchant($transaction->description)) {
                $transaction->merchantId = $merchant->id;
                $this->success("Found existing merchant {$merchant->id} ({$merchant->name})");
            } else {
                $merchant = $this->createMerchant($transaction->description);
                $transaction->merchantId = $merchant->id;
                $this->warn("Created new merchant {$merchant->id} ({$merchant->name})");
            }

            $saved = $this->saveTransaction($transaction);
            if ($saved === 1) {
                $this->success("Inserted transaction {$transaction->hash}.");
            } elseif ($saved === 2) {
                $this->warn("Updated transaction {$transaction->hash}.");
            }  elseif ($saved === 0) {
                $this->error("Nothing changed in transaction {$transaction->hash}.");
            }
        }
    }

    public function saveTransaction(Transaction $transaction)
    {
        return $this->db->affectingStatement(
            "INSERT INTO transactions
            (cardId, date, description, amount, merchantId, raw, hash)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE merchantId = VALUES(merchantId)",
            [
                $transaction->cardId,
                $transaction->date,
                $transaction->description,
                $transaction->amount,
                $transaction->merchantId,
                $transaction->raw,
                $transaction->hash,
            ]
        );
    }

    public function getMerchant(string $description): ?Merchant
    {
        $query = "SELECT m.*
            FROM merchants m
            LEFT JOIN merchant_aliases ma ON ma.merchantId = m.id
            WHERE LOCATE(m.name, ?) OR LOCATE(ma.name, ?)
            GROUP BY m.id
            ORDER BY auto ASC
            LIMIT 1";

        $merchant = Merchant::fromQuery(
            $query,
            [
                $description,
                $description,
            ]
        );

        return $merchant->first();
    }

    protected function createMerchant(string $description): Merchant
    {
        $merchant = new Merchant(
            [
                'auto' => 1,
                'name' => $description,
            ]
        );
        $merchant->save();

        return $merchant;
    }

    protected function info($string)
    {
        echo '<p>'.$string.'</p>'.PHP_EOL;
    }

    protected function warn($string)
    {
        echo '<p class="text-warning">'.$string.'</p>'.PHP_EOL;
    }

    protected function error($string)
    {
        echo '<p class="text-danger">'.$string.'</p>'.PHP_EOL;
    }

    protected function success($string)
    {
        echo '<p class="text-success">'.$string.'</p>'.PHP_EOL;
    }
}

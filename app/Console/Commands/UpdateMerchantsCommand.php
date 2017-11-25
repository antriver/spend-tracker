<?php

namespace SpendTracker\Console\Commands;

use Illuminate\Console\Command;
use SpendTracker\Libraries\TransactionImporter;
use SpendTracker\Models\Merchant;
use SpendTracker\Models\Transaction;

class UpdateMerchantsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the merchant on each transaction if a non-auto one is available.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param TransactionImporter $transactionImporter
     *
     * @return mixed
     */
    public function handle(TransactionImporter $transactionImporter)
    {
        /** @var Transaction[] $transactions */
        $transactions = Transaction::fromQuery(
            'SELECT t.* 
             FROM transactions t
             LEFT JOIN merchants m ON m.id = t.merchantId'
        );

        foreach ($transactions as $transaction) {
            $newMerchant = $transactionImporter->getMerchant($transaction->description);
            if ($newMerchant && $newMerchant->id != $transaction->merchantId) {
                $oldMerchantId = $transaction->merchantId;
                $transaction->merchantId = $newMerchant->id;
                $this->output->success(
                    "Changed transaction {$transaction->id} ({$transaction->description}) merchant from"
                    ." {$oldMerchantId} to {$newMerchant->id} ({$newMerchant->name})"
                );
                $transactionImporter->saveTransaction($transaction);
            }
        }

        // Delete now unused merchants.
        /** @var Merchant[] $merchants */
        $merchants = Merchant::fromQuery(
            'SELECT m.*
             FROM merchants m
             LEFT JOIN transactions t ON t.merchantId = m.id
             GROUP BY m.id
             HAVING COUNT(t.id) = 0'
        );

        foreach ($merchants as $merchant) {
            if ($merchant->delete()) {
                $this->output->success("Deleted now-used merchant {$merchant->id} ($merchant->name)");
            }
        }
    }
}

<?php

namespace SpendTracker\Libraries;

use Exception;
use Illuminate\Support\Collection;
use SpendTracker\Libraries\Providers\AbstractProvider;
use SpendTracker\Libraries\Providers\AmexProvider;
use SpendTracker\Libraries\Providers\HalifaxProvider;
use SpendTracker\Libraries\Providers\MbnaProvider;
use SpendTracker\Models\Card;
use SpendTracker\Models\Transaction;

class TransactionFactory
{
    protected $providers = [
        Card::PROVIDER_AMEX => AmexProvider::class,
        Card::PROVIDER_HALIFAX => HalifaxProvider::class,
        Card::PROVIDER_MBNA => MbnaProvider::class,
    ];

    /**
     * @param Card $card
     * @param string $csv
     *
     * @return Collection|Transaction[]
     */
    public function createTransactions(Card $card, string $csv): Collection
    {
        $provider = $this->getProvider($card);

        $transactions = $provider->createTransactionsForFile($card, $csv);

        return $transactions;
    }

    /**
     * @param Card $card
     *
     * @return AbstractProvider
     * @throws Exception
     */
    protected function getProvider(Card $card): AbstractProvider
    {
        if (isset($this->providers[$card->provider])) {
            $providerClass = $this->providers[$card->provider];

            return new $providerClass();
        }

        throw new Exception("Unknown provider {$card->provider}.");
    }
}

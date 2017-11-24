<?php

namespace SpendTracker\Models;

/**
 * SpendTracker\Models\Card
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $provider
 * @property-read \Illuminate\Database\Eloquent\Collection|\SpendTracker\Models\Transaction[] $transactions
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Card whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Card whereProvider($value)
 * @mixin \Eloquent
 */
class Card extends AbstractModel
{
    const PROVIDER_AMEX = 'amex';
    const PROVIDER_HALIFAX = 'halifax';
    const PROVIDER_MBNA = 'mbna';

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cardId', 'id');
    }
}

<?php

namespace SpendTracker\Models;

/**
 * SpendTracker\Models\Transaction
 *
 * @property int $id
 * @property int|null $cardId
 * @property string $date
 * @property string|null $description
 * @property float $amount
 * @property int|null $merchantId
 * @property string|null $raw
 * @property string|null $hash
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon|null $updatedAt
 * @property-read \SpendTracker\Models\Merchant|null $merchant
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereRaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends AbstractModel
{
    protected $dates = [
        'date',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class, 'cardId', 'id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchantId', 'id');
    }

    public function setAmountAttribute(float $amount)
    {
        $this->attributes['amount'] = $amount;
    }

    public function setDescriptionAttribute(string $description)
    {
        $this->attributes['description'] = strtoupper(trim($description));
    }

    public function setRawAttribute(string $raw)
    {
        $this->attributes['raw'] = $raw;
        $this->hash = $this->generateHash();
    }

    public function generateHash()
    {
        return md5($this->raw);
    }
}

<?php

namespace SpendTracker\Models;

/**
 * SpendTracker\Models\Merchant
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $categoryId
 * @property int $auto
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon|null $updatedAt
 * @property-read \SpendTracker\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\SpendTracker\Models\Transaction[] $transactions
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Merchant whereAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Merchant whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Merchant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Merchant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Merchant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Merchant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Merchant extends AbstractModel
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'merchantId', 'id');
    }
}

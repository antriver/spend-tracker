<?php

namespace SpendTracker\Models;

/**
 * SpendTracker\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property int|null $parentCategoryId
 * @property-read \Illuminate\Database\Eloquent\Collection|\SpendTracker\Models\Merchant[] $merchants
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\SpendTracker\Models\Category whereParentCategoryId($value)
 * @mixin \Eloquent
 */
class Category extends AbstractModel
{
    public $table = 'categories';

    public function merchants()
    {
        return $this->hasMany(Merchant::class, 'categoryId', 'id');
    }
}

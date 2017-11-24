<?php

namespace SpendTracker\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
abstract class AbstractModel extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $guarded = [];
}

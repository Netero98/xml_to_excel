<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string id
 * @property bool available
 * @property string url
 * @property int price
 * @property null|int oldprice
 * @property int currency_id
 * @property int category_id
 * @property null|string picture
 * @property string name
 * @property null|string vendor
 *
 * @property Category category
 */
class Offer extends Model
{
    public const PROP_CATEGORY_ID = 'category_id';

    public function category(): HasOne
    {
        return $this->hasOne(
            Category::class,
            Category::PROP_ID,
            self::PROP_CATEGORY_ID,
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string id_foreign
 * @property bool available
 * @property string url
 * @property int price
 * @property null|int oldprice
 * @property int currency_id
 * @property null|string picture
 * @property string name
 * @property null|string vendor
 * @property string category
 * @property string sub_category
 * @property null|string sub_sub_category
 */
class Offer extends Model
{
    public const PROP_ID_FOREIGN = 'id_foreign';
    public const PROP_NAME = 'name';
    public const PROP_CATEGORY = 'category';
    public const PROP_SUB_CATEGORY = 'sub_category';
    public const PROP_SUB_SUB_CATEGORY = 'sub_sub_category';
    public const PROP_URL = 'url';
    public const PROP_PRICE = 'price';
    public const PROP_OLDPRICE = 'oldprice';
    public const PROP_CURRENCY_ID = 'currency_id';
    public const PROP_PICTURE = 'picture';
    public const PROP_VENDOR = 'vendor';
    public const PROP_AVAILABLE = 'available';
}

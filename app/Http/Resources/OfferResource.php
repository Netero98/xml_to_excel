<?php

namespace App\Http\Resources;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public static function getResourceKeys()
    {
        return [
            Offer::PROP_ID_FOREIGN,
            Offer::PROP_NAME,
            Offer::PROP_CATEGORY,
            Offer::PROP_SUB_CATEGORY,
            Offer::PROP_SUB_SUB_CATEGORY,
            Offer::PROP_URL,
            Offer::PROP_PRICE,
            Offer::PROP_OLDPRICE,
            Offer::PROP_CURRENCY_ID,
            Offer::PROP_PICTURE,
            Offer::PROP_VENDOR,
            Offer::PROP_AVAILABLE,
        ];
    }

    public function toArray(Request $request)
    {
        /**
         * @var Offer $offer
         */
        $offer = $this->resource;

        return [
            Offer::PROP_ID_FOREIGN => $offer->id_foreign,
            Offer::PROP_NAME => $offer->name,
            Offer::PROP_CATEGORY => $offer->category,
            Offer::PROP_SUB_CATEGORY => $offer->sub_category,
            Offer::PROP_SUB_SUB_CATEGORY => $offer->sub_sub_category,
            Offer::PROP_URL => $offer->url,
            Offer::PROP_PRICE => $offer->price,
            Offer::PROP_OLDPRICE => $offer->oldprice,
            Offer::PROP_CURRENCY_ID => $offer->currency_id,
            Offer::PROP_PICTURE => $offer->picture,
            Offer::PROP_VENDOR => $offer->vendor,
            Offer::PROP_AVAILABLE =>$offer->available,
        ];
    }
}

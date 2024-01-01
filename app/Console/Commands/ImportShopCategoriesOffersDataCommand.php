<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Shop;
use App\Services\XmlToArrayService;
use Illuminate\Console\Command;

class ImportShopCategoriesOffersDataCommand extends Command
{
    private const CATEGORY_PROP_ID = '@id';
    private const CATEGORY_PROP_PARENT_ID = '@parentId';
    private const CATEGORY_PROP_NAME = '$';

    private const SHOP_PROP_NAME = 'name';
    private const SHOP_PROP_COMPANY = 'company';
    private const SHOP_PROP_URL = 'url';
    private const SHOP_PROP_PLATFORM = 'platform';
    private const SHOP_PROP_VERSION = 'version';
    private const SHOP_PROP_CATEGORIES = 'categories';
    private const SHOP_PROP_OFFERS = 'offers';

    private const CATEGORIES_PROP_CATEGORY = 'category';

    private const OFFERS_PROP_OFFER = 'offer';

    private const OFFER_PROP_ID = '@id';
    private const OFFER_PROP_AVAILABLE = '@available';
    private const OFFER_PROP_URL = 'url';
    private const OFFER_PROP_PRICE = 'price';
    private const OFFER_PROP_OLD_PRICE = 'oldprice';
    private const OFFER_PROP_CURRENCY_ID = 'currencyId';
    private const OFFER_PROP_CATEGORY_ID = 'categoryId';
    private const OFFER_PROP_PICTURE = 'picture';
    private const OFFER_PROP_NAME = 'name';
    private const OFFER_PROP_VENDOR = 'vendor';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-offers-xml {url} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //todo: transaction
        $url = $this->argument('url');
        $isDebug = $this->option('debug');

        $array = (new XmlToArrayService())->xmlToArray(
            new \SimpleXMLElement($this->getXmlFrom($url, $isDebug))
        );

        $shopData = $array['yml_catalog']['shop'];

        $shop = new Shop();
        $shop->name = $shopData[self::SHOP_PROP_NAME];
        $shop->company = $shopData[self::SHOP_PROP_COMPANY];
        $shop->url = $shopData[self::SHOP_PROP_URL];
        $shop->platform = $shopData[self::SHOP_PROP_PLATFORM];
        $shop->version = $shop[self::SHOP_PROP_VERSION];

        $shop->save();
        $shop->fresh();

        $allCategories = $shopData[self::SHOP_PROP_CATEGORIES][self::CATEGORIES_PROP_CATEGORY];

        foreach ($allCategories as $categoryArr) {
            $category = new Category();
            $category->id = $categoryArr[self::CATEGORY_PROP_ID];
            $category->shop_id = $shop->id;
            $category->parent_id = $categoryArr[self::CATEGORY_PROP_PARENT_ID] ?? null;
            $category->name = $categoryArr[self::CATEGORY_PROP_NAME];

            $category->save();
        }

        $allOffers = $shopData[self::SHOP_PROP_OFFERS][self::OFFERS_PROP_OFFER];

        foreach ($allOffers as $offerArr) {
            $offer = new Offer();
            $offer->id = $offerArr[self::OFFER_PROP_ID];
            $offer->available = $offerArr[self::OFFER_PROP_AVAILABLE] === 'true';
            $offer->url = $offerArr[self::OFFER_PROP_URL];
            $offer->price = $offerArr[self::OFFER_PROP_PRICE];
            $offer->oldprice = $offerArr[self::OFFER_PROP_OLD_PRICE] ?? null;
            $offer->currency_id = $offerArr[self::OFFER_PROP_CURRENCY_ID];
            $offer->category_id = $offerArr[self::OFFER_PROP_CATEGORY_ID];
            $offer->picture = $offerArr[self::OFFER_PROP_PICTURE] ?? null;
            $offer->name = $offerArr[self::OFFER_PROP_NAME];
            //todo: make detached command for creating special table for this purpose
//            $offer->categories_data = Category::query()
//                ->find($offerArr[self::OFFER_PROP_CATEGORY_ID])
//                ->findParentsHierarchy();
            $offer->vendor = isset($offerArr[self::OFFER_PROP_VENDOR]) && $offerArr[self::OFFER_PROP_VENDOR] !== 'none'
                ? $offerArr[self::OFFER_PROP_VENDOR]
                : null;

            $offer->save();
        }
    }

    private function getXmlFrom(string $url, bool $debug): string
    {
        $fileStoragePath = 'console/goods.xml';

        if (!$debug) {
            return file_get_contents($url);
        }

        if (file_exists(storage_path($fileStoragePath))) {
            $file = file_get_contents(storage_path($fileStoragePath));
        } else {
            $file = file_get_contents($url);
            file_put_contents(storage_path( $fileStoragePath), $file);
        }

        return $file;
    }
}

<?php

namespace App\Services;

use App\Exceptions\DbTransactionRolledBackException;
use App\Models\Offer;
use Exception;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

class RefreshOffersDataService
{
    public function __construct(
        private readonly XmlToArrayService $xmlService,
    ) {
    }

    private const CATEGORY_PROP_ID = '@id';
    private const CATEGORY_PROP_PARENT_ID = '@parentId';
    private const CATEGORY_PROP_NAME = '$';

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

    private const CATEGORY_HIERARCHY_0_LVL = 'category';
    private const CATEGORY_HIERARCHY_1_LVL = 'sub_category';
    private const CATEGORY_HIERARCHY_2_LVL = 'sub_sub_category';

    public function flushOffers() {
        DB::statement(sprintf('truncate table %s', Offer::TABLE));
    }

    /**
     * @throws DbTransactionRolledBackException
     * @throws Exception
     */
    public function refreshOffersFromDefaultUrl(bool $debug = false): void
    {
        $url = config('url.offers_xml_source');

        $this->refreshFromUrlWithXml($url, $debug);
    }

    /**
     * @throws DbTransactionRolledBackException
     * @throws Exception
     */
    public function refreshFromUrlWithXml(string $url, bool $isDebug): void
    {
        $array = $this->xmlService->xmlToArray(
            new SimpleXMLElement($this->getXmlFrom($url, $isDebug))
        );

        $shopData = $array['yml_catalog']['shop'];

        $allCategories = $shopData[self::SHOP_PROP_CATEGORIES][self::CATEGORIES_PROP_CATEGORY];

        $allCategoriesIdsMap = [];

        foreach ($allCategories as $categoryArr) {
            $allCategoriesIdsMap[$categoryArr[self::CATEGORY_PROP_ID]] = $categoryArr;
        }

        $allCategoriesHierarchies = $this->getAllCategoriesHierarchies($allCategoriesIdsMap);

        $allOffers = $shopData[self::SHOP_PROP_OFFERS][self::OFFERS_PROP_OFFER];

        try {
            DB::transaction(function () use ($allOffers, $allCategoriesHierarchies) {
                DB::statement(sprintf('truncate table %s', Offer::TABLE));

                foreach ($allOffers as $offerArr) {
                    $offer = new Offer();
                    $offer->id_foreign = $offerArr[self::OFFER_PROP_ID];
                    $offer->available = $offerArr[self::OFFER_PROP_AVAILABLE] === 'true';
                    $offer->url = $offerArr[self::OFFER_PROP_URL];
                    $offer->price = $offerArr[self::OFFER_PROP_PRICE];
                    $offer->oldprice = $offerArr[self::OFFER_PROP_OLD_PRICE] ?? null;
                    $offer->currency_id = $offerArr[self::OFFER_PROP_CURRENCY_ID];
                    $offer->picture = $offerArr[self::OFFER_PROP_PICTURE] ?? null;
                    $offer->name = $offerArr[self::OFFER_PROP_NAME];
                    $offer->category = $allCategoriesHierarchies[$offerArr[self::OFFER_PROP_CATEGORY_ID]][self::CATEGORY_HIERARCHY_0_LVL];
                    $offer->sub_category = $allCategoriesHierarchies[$offerArr[self::OFFER_PROP_CATEGORY_ID]][self::CATEGORY_HIERARCHY_1_LVL];
                    $offer->sub_sub_category = $allCategoriesHierarchies[$offerArr[self::OFFER_PROP_CATEGORY_ID]][self::CATEGORY_HIERARCHY_2_LVL] ?? null;
                    $offer->vendor = isset($offerArr[self::OFFER_PROP_VENDOR]) && $offerArr[self::OFFER_PROP_VENDOR] !== 'none'
                        ? $offerArr[self::OFFER_PROP_VENDOR]
                        : null;

                    if (!$offer->save()) {
                        throw new Exception();
                    }
                }
            });
        } catch (\Throwable) {
            throw new DbTransactionRolledBackException();
        }
    }

    private function getAllCategoriesHierarchies(array $allCategoriesIdsMap): array
    {
        $result = [];

        $allIds = array_keys($allCategoriesIdsMap);

        foreach ($allIds as $id) {
            $hierarchy = $this->getHierarchyForCategory($allCategoriesIdsMap, $id);

            $result[$id] = $this->makeHierarchyWithSpecialKeys($hierarchy);
        }

        return $result;
    }

    /**
     * @return string[] - array of names
     */
    private function getHierarchyForCategory(array $allCategoriesIdsMap, mixed $categoryId): array
    {
        $catArr = $allCategoriesIdsMap[$categoryId] ?? null;

        if (!$catArr) {
            return [];
        }

        $hierarchy = [$catArr[self::CATEGORY_PROP_NAME]];

        return array_merge(
            $hierarchy,
            $this->getHierarchyForCategory(
                $allCategoriesIdsMap,
                $catArr[self::CATEGORY_PROP_PARENT_ID] ?? null
            )
        );
    }

    /**
     * @param string[] $hierarchyNames
     */
    private function makeHierarchyWithSpecialKeys(array $hierarchyNames): array
    {
        $result = [];
        $key = self::CATEGORY_HIERARCHY_0_LVL;

        $reversed = array_reverse($hierarchyNames);

        foreach ($reversed as $categoryName) {
            $result[$key] = $categoryName;
            $key = 'sub_' . $key;
        }

        return $result;
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

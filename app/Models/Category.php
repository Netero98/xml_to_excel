<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int shop_id
 * @property null|int parent_id
 * @property string name
 */
class Category extends Model
{
    public const PROP_ID = 'id';

    public function findParentsHierarchy(): array
    {
        $defaultResult = ['category' => $this->name];

        if (!$this->parent_id) {
            return $defaultResult;
        }

        /**
         * @var $parent Category
         */
        $parent = Category::query()->find($this->parent_id);

        if (!$parent) {
            return $defaultResult;
        }

        $parentsHierarchy = $parent->findParentsHierarchy();

        $keyLast = array_key_last($parentsHierarchy);

        if (!$keyLast) {
            return $defaultResult;
        }

        $multiSubKey = 'sub_' . $keyLast;

        return array_merge(
            $parentsHierarchy,
            [
                $multiSubKey => $this->name
            ]
        );
    }
}

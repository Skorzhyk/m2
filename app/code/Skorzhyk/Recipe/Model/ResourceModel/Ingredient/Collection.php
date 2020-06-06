<?php

namespace Skorzhyk\Recipe\Model\ResourceModel\Ingredient;

use Skorzhyk\Recipe\Model\Ingredient;
use Skorzhyk\Recipe\Model\ResourceModel\Ingredient as ResourceIngredient;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Skorzhyk\Recipe\Model\ResourceModel\Ingredient
 */
class Collection extends AbstractCollection
{
//    /**
//     * @var string
//     */
//    const MAIN_TABLE_ALIAS = 'main_table.';
//
//    /**
//     * @var string
//     */
//    const RECIPE_ID_FIELD = 'recipe_id';
//
//    /**
//     * @var string
//     */
//    const CUSTOMER_ID_FIELD = 'customer_id';
//
//    /**
//     * @var string
//     */
//    const STATUS_FIELD = 'status';
//
//    /**
//     * @var string
//     */
//    const TITLE_FIELD = 'title';
//
//    /**
//     * @var string
//     */
//    const FILE_FIELD = 'file';
//
//    /**
//     * @var string
//     */
//    const CREATED_AT_FIELD = 'created_at';
//
//    /**
//     * @var string
//     */
//    const UPDATED_AT_FIELD = 'updated_at';
//
//    /**
//     * @var string
//     */
//    protected $_idFieldName = 'recipe_id';

    public function _construct()
    {
        $this->_init(Ingredient::class, ResourceIngredient::class);
    }
}

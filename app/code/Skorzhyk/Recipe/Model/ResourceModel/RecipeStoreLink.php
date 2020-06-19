<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Implementation of basic operations for RecipeStoreLink entity for specific db layer
 * This class needed for internal purposes only, to make collection work properly
 */
class RecipeStoreLink extends AbstractDb
{
    /** @var string */
    const TABLE_NAME_RECIPE_STORE_LINK = 'skorzhyk_recipe_store';

    /** @var string */
    const ID_FIELD_NAME = 'link_id';

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_RECIPE_STORE_LINK, self::ID_FIELD_NAME);
    }
}

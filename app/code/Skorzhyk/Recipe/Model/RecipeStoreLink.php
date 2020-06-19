<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Inventory\Model\ResourceModel\SourceCarrierLink as SourceCarrierLinkResourceModel;

/**
 * Class RecipeStoreLink
 * @package Magento\Inventory\Model
 */
class RecipeStoreLink extends AbstractExtensibleModel
{
    /** @var string */
    const RECIPE_ID = 'recipe_id';

    /** @var string */
    const STORE_ID = 'store_id';

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(SourceCarrierLinkResourceModel::class);
    }

    /**
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }
}

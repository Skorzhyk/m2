<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model\ResourceModel\RecipeStoreLink;

use Skorzhyk\Recipe\Model\RecipeStoreLink as RecipeStoreLinkModel;
use Skorzhyk\Recipe\Model\ResourceModel\RecipeStoreLink as RecipeStoreLinkResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Resource Collection of RecipeStoreLink entities
 * It is not an API because RecipeStoreLink must be loaded via Recipe entity only
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(RecipeStoreLinkModel::class, RecipeStoreLinkResourceModel::class);
    }
}

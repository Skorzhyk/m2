<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model\ResourceModel\Recipe\Grid;

use Skorzhyk\Recipe\Model\ResourceModel\Recipe;
use Skorzhyk\Recipe\Model\ResourceModel\Recipe\Collection as GridCollection;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;

/**
 * Class Collection
 * @package Skorzhyk\Recipe\Model\ResourceModel\Recipe\Grid
 */
class Collection extends GridCollection
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _construct()
    {
        $this->_init(Document::class, Recipe::class);
    }
}

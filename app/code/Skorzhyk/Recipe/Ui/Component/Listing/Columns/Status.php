<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Ui\Component\Listing\Columns;

use Skorzhyk\Recipe\Model\Config\Source\RecipeConfig;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Skorzhyk\Recipe\Ui\Component\Listing\Columns
 */
class Status implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => RecipeConfig::NOT_VALIDATE_STATUS, 'label' => __('Not validate')],
            ['value' => RecipeConfig::VALIDATE_STATUS, 'label' => __('Validate')]
        ];
    }
}

<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Ui\Component\Listing\Columns;

use Skorzhyk\ChefInfo\Model\Config\Source\ChefList;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Chef
 * @package Skorzhyk\Recipe\Ui\Component\Listing\Columns
 */
class Chef implements OptionSourceInterface
{
    /**
     * @var ChefList
     */
    private $chefList;

    /**
     * Chef constructor.
     * @param ChefList $chefList
     */
    public function __construct(ChefList $chefList)
    {
        $this->chefList = $chefList;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        return $this->chefList->getAllOptions();
    }
}

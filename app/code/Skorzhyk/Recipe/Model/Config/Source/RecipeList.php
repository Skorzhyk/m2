<?php

namespace Fidesio\Recipe\Model\Config\Source;

use Skorzhyk\Recipe\Model\ResourceModel\Recipe\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class RecipeList
 * @package Fidesio\ChefInfo\Model\Config\Source
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RecipeList extends AbstractSource
{
    /** @var CollectionFactory */
    private $recipeCollection;

    /**
     * RecipeList constructor.
     * @param CollectionFactory $recipeCollection
     */
    public function __construct(CollectionFactory $recipeCollection)
    {
        $this->recipeCollection = $recipeCollection;
    }

    /**
     * @param null|bool $withEmpty
     * @return array
     */
    public function getAllOptions($withEmpty = null): array
    {
        $collection = $this->recipeCollection->create();
        $collection->addFieldToFilter('status', RecipeConfig::VALIDATE_STATUS);
        $recipes = $collection->getItems();

        $options = [];

        if ($withEmpty !== false) {
            $options[] = ['label' => __('-- Please Select a Recipe(s) --'), 'value' => ''];
        }

        foreach ($recipes as $recipe) {
            $options[] = ['label' => $recipe->getTitle(), 'value' => $recipe->getRecipeId()];
        }

        return $options;
    }
}

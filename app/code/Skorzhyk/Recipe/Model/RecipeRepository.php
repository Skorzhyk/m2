<?php

namespace Fidesio\Recipe\Model;

use Exception;
use Skorzhyk\Recipe\Model\ResourceModel\Recipe as ResourceRecipe;
use Skorzhyk\Recipe\Model\ResourceModel\Recipe\CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class RecipeRepository
 * @package Fidesio\Recipe\Model
 */
class RecipeRepository
{
    /**
     * @var CollectionFactory
     */
    public $collectionFactory;

    /**
     * @var ResourceRecipe
     */
    protected $resource;

    /**
     * @var RecipeFactory
     */
    protected $recipeFactory;

    /**
     * RecipeRepository constructor.
     * @param CollectionFactory $collectionFactory
     * @param ResourceRecipe $resource
     * @param RecipeFactory $recipeFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceRecipe $resource,
        RecipeFactory $recipeFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resource = $resource;
        $this->recipeFactory = $recipeFactory;
    }

    /**
     * @param Recipe $recipe
     * @return Recipe
     * @throws CouldNotSaveException
     */
    public function save(Recipe $recipe)
    {
        try {
            $this->resource->save($recipe);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the recipe: %1', $exception->getMessage()),
                $exception
            );
        }
        return $recipe;
    }

    /**
     * @param mixed $recipeId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($recipeId)
    {
        return $this->delete($this->getById($recipeId));
    }

    /**
     * @param Recipe $recipe
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Recipe $recipe)
    {
        try {
            $this->resource->delete($recipe);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the recipe: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @param mixed $recipeId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($recipeId)
    {
        $recipe = $this->recipeFactory->create();
        $this->resource->load($recipe, $recipeId);
        if (!$recipe->getId()) {
            throw new NoSuchEntityException(__('Recipe with id "%1" does not exist.', $recipeId));
        }
        return $recipe;
    }

    /**
     * @param string|null $chefId
     * @return DataObject[]
     */
    public function getListByChefId($chefId = null)
    {
        $collection = $this->collectionFactory->create();
        if ($chefId) {
            $collection->addFieldToFilter('customer_id', $chefId);
        }
        return $collection->getItems();
    }

    /**
     * @param array $ids
     * @return DataObject[]
     */
    public function getListByIds($ids = null)
    {
        $collection = $this->collectionFactory->create();
        if ($ids) {
            $collection->addFieldToFilter('recipe_id', ['in' => $ids]);
        }
        return $collection->getItems();
    }
}

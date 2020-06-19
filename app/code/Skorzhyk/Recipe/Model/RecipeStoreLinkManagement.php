<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model;

use Skorzhyk\Recipe\Model\RecipeStoreLink;
use Skorzhyk\Recipe\Model\ResourceModel\RecipeStoreLink as ResourceRecipeStoreLink;
use Skorzhyk\Recipe\Model\ResourceModel\RecipeStoreLink\Collection;
use Skorzhyk\Recipe\Model\ResourceModel\RecipeStoreLink\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ResourceConnection;

/**
 * Class RecipeStoreLinkManagement
 * @package Skorzhyk\Recipe\Model
 */
class RecipeStoreLinkManagement
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var ResourceRecipeStoreLink
     */
    private $recipeStoreLinkResource;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private $storeLinkCollectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * RecipeStoreLinkManagement constructor.
     * @param ResourceConnection $resourceConnection
     * @param ResourceRecipeStoreLink $recipeStoreLinkResource
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $storeLinkCollectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ResourceRecipeStoreLink $recipeStoreLinkResource,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $storeLinkCollectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->recipeStoreLinkResource = $recipeStoreLinkResource;
        $this->collectionProcessor = $collectionProcessor;
        $this->storeLinkCollectionFactory = $storeLinkCollectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param Recipe $recipe
     * @return void
     */
    public function saveStoreLinksByRecipe(Recipe $recipe): void
    {
        $this->deleteCurrentStoreLinks($recipe);

        $storeLinks = $recipe->getStoreLinks();
        if (null !== $storeLinks && count($storeLinks)) {
            $this->saveNewStoreLinks($recipe);
        }
    }

    /**
     * @param Recipe $recipe
     * @return void
     */
    private function deleteCurrentStoreLinks(Recipe $recipe)
    {
        $connection = $this->resourceConnection->getConnection();
        $connection->delete(
            $this->resourceConnection->getTableName(ResourceRecipeStoreLink::TABLE_NAME_RECIPE_STORE_LINK),
            $connection->quoteInto('recipe_id = ?', $recipe->getRecipeId())
        );
    }

    /**
     * @param Recipe $recipe
     * @return void
     */
    private function saveNewStoreLinks(Recipe $recipe)
    {
        $storeLinkData = [];
        foreach ($recipe->getStoreLinks() as $storeLink) {
            $storeLinkData[] = [
                RecipeStoreLink::RECIPE_ID => $recipe->getRecipeId(),
                RecipeStoreLink::STORE_ID => $storeLink->getStoreId()
            ];
        }

        $this->resourceConnection->getConnection()->insertMultiple(
            $this->resourceConnection->getTableName(ResourceRecipeStoreLink::TABLE_NAME_RECIPE_STORE_LINK),
            $storeLinkData
        );
    }

    /**
     * Load store links by recipe and set its to recipe object
     *
     * @param Recipe $recipe
     * @return void
     */
    public function loadStoreLinksByRecipe(Recipe $recipe): void
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(RecipeStoreLink::RECIPE_ID, $recipe->getRecipeId())
            ->create();

        /** @var Collection $collection */
        $collection = $this->storeLinkCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $recipe->setStoreLinks($collection->getItems());
    }
}

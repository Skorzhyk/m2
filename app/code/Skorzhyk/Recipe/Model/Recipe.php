<?php

namespace Skorzhyk\Recipe\Model;

use Skorzhyk\Recipe\Model\ResourceModel\Recipe as RecipeResource;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Recipe
 * @package Skorzhyk\Recipe\Model
 */
class Recipe extends AbstractModel
{
//    /** @var string */
//    const STORE_LINKS = 'store_links';
//
//    /** @var string */
//    const RECIPE_ID = 'recipe_id';
//
//    /** @var string */
//    const STATUS = 'status';
//
//    /**
//     * @var ResourceRecipe
//     */
//    private $resourceRecipe;
//
//    /**
//     * @var RecipeEntity
//     */
//    private $recipeEntity;
//
//    /**
//     * @var RecipeStoreLinkFactory
//     */
//    private $recipeStoreLinkFactory;
//    /**
//     * @var Status
//     */
//    private $status;

//    /**
//     * Recipe constructor.
//     * @param Context $context
//     * @param Registry $registry
//     * @param ResourceRecipe $resourceRecipe
//     * @param RecipeEntity $recipeEntity
//     * @param RecipeStoreLinkFactory $recipeStoreLinkFactory
//     * @param Status $status
//     * @param AbstractResource|null $resource
//     * @param AbstractDb|null $resourceCollection
//     * @param array $data
//     */
//    public function __construct(
//        Context $context,
//        Registry $registry,
//        ResourceRecipe $resourceRecipe,
//        RecipeEntity $recipeEntity,
//        RecipeStoreLinkFactory $recipeStoreLinkFactory,
//        Status $status,
//        AbstractResource $resource = null,
//        AbstractDb $resourceCollection = null,
//        array $data = []
//    ) {
//        $this->resourceRecipe = $resourceRecipe;
//        $this->recipeEntity = $recipeEntity;
//        $this->recipeStoreLinkFactory = $recipeStoreLinkFactory;
//        $this->status = $status;
//        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
//    }

    public function _construct()
    {
        $this->_init(RecipeResource::class);
    }

//    /**
//     * @return array|null
//     */
//    public function getStoreLinks()
//    {
//        return $this->getData(self::STORE_LINKS);
//    }
//
//    /**
//     * Get Recipe Id
//     *
//     * @return string|null
//     */
//    public function getRecipeId()
//    {
//        return $this->getData(self::RECIPE_ID);
//    }
//
//    /**
//     * Get Recipe Status Label
//     *
//     * @return string
//     */
//    public function getStatusLabel()
//    {
//        $status = $this->getData(self::STATUS);
//        $options = $this->status->toOptionArray();
//        $statusLabel = '';
//        foreach ($options as $option) {
//            if ($option['value'] == $status) {
//                $statusLabel = $option['label'];
//                break;
//            }
//        }
//        return $statusLabel;
//    }
//
//    /**
//     * Save recipe information from admin.
//     *
//     * @param array $recipeInfo
//     * @return $this
//     * @throws AlreadyExistsException
//     */
//    public function saveRecipeFromAdmin($recipeInfo)
//    {
//        $recipeId = isset($recipeInfo['recipe_id']) ? $recipeInfo['recipe_id'] : null;
//        if ($recipeId) {
//            $this->resourceRecipe->load($this, $recipeId);
//        }
//
//        $this->saveRecipe($recipeInfo);
//
//        return $this;
//    }
//
//    /**
//     * Save recipe.
//     *
//     * @param array $recipeInfo
//     * @return $this
//     * @throws AlreadyExistsException
//     */
//    private function saveRecipe($recipeInfo)
//    {
//        $chefId = $recipeInfo[Collection::CUSTOMER_ID_FIELD] ?: null;
//        $this->setCustomerId($chefId);
//        $this->setTitle(htmlspecialchars(trim($recipeInfo[Collection::TITLE_FIELD])));
//        $this->setFile($recipeInfo['file_name']);
//        $status = $recipeInfo[Collection::STATUS_FIELD] ?? RecipeConfig::NOT_VALIDATE_STATUS;
//        $this->setStatus($status);
//
//        $storeLinks = [];
//        foreach ($recipeInfo['store_id'] as $storeId) {
//            $recipeStoreLink = $this->recipeStoreLinkFactory->create();
//            $recipeStoreLink->setStoreId($storeId);
//            $storeLinks[] = $recipeStoreLink;
//        }
//        if ($storeLinks) {
//            $this->setStoreLinks($storeLinks);
//        }
//
//        $this->resourceRecipe->save($this);
//
//        return $this;
//    }
//
//    /**
//     * @param array|null $storeLinks
//     * @return void
//     */
//    public function setStoreLinks($storeLinks): void
//    {
//        $this->setData(self::STORE_LINKS, $storeLinks);
//    }
//
//    /**
//     * Save chef information from my account.
//     *
//     * @param array $params
//     * @return $this
//     * @throws AlreadyExistsException
//     * @throws InputException
//     */
//    public function saveRecipeFromMyAccount($params)
//    {
//        if ($params['file_name'] === '') {
//            throw new InputException(
//                __('File name is required. Please, try again.')
//            );
//        }
//        $this->saveRecipe($params);
//
//        return $this;
//    }
//
//    /**
//     * Delete a recipe.
//     *
//     * @param DataObject $recipe
//     * @return $this
//     * @throws Exception
//     */
//    public function deleteItem($recipe)
//    {
//        $this->resourceRecipe->load($this, $recipe->getId());
//        $this->resourceRecipe->delete($this);
//
//        return $this;
//    }
}

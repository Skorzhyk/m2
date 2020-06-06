<?php

namespace Skorzhyk\Recipe\Model\Recipe;

use Skorzhyk\Recipe\Model\ResourceModel\Recipe\Step as ResourceStep;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Recipe
 * @package Skorzhyk\Recipe\Model\Recipe
 */
class Step extends AbstractModel
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

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _construct()
    {
        $this->_init(ResourceStep::class);
    }
}

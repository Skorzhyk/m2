<?php

namespace Skorzhyk\Recipe\Model\ResourceModel;

use Exception;
use Skorzhyk\Recipe\Model\Recipe as RecipeModel;
use Skorzhyk\Recipe\Model\ResourceModel\Recipe\Step as StepResource;
use Skorzhyk\Recipe\Model\ResourceModel\Recipe\Step\CollectionFactory as StepCollectionFactory;
use Skorzhyk\Recipe\Model\ResourceModel\Ingredient\CollectionFactory as IngredientCollectionFactory;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Recipe
 * @package Skorzhyk\Recipe\Model\ResourceModel
 */
class Recipe extends AbstractDb
{
    /**
     * @var string
     */
    const RECIPE_TABLE = 'skorzhyk_recipe';

    const RECIPE_ID_FIELD = 'recipe_id';

    const INGREDIENT_ID_FIELD = 'ingredient_id';

    const RECIPE_INGREDIENT_TABLE = 'skorzhyk_recipe_ingredient';

    protected $stepResource;

    protected $ingredientCollectionFactory;

    protected $stepCollectionFactory;

    /**
     * Recipe constructor.
     * @param Context $context
     * @param StepResource $stepResource
     * @param IngredientCollectionFactory $ingredientCollectionFactory
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        StepResource $stepResource,
        StepCollectionFactory $stepCollectionFactory,
        IngredientCollectionFactory $ingredientCollectionFactory,
        $connectionName = null
    ) {
        $this->stepResource = $stepResource;
        $this->stepCollectionFactory = $stepCollectionFactory;
        $this->ingredientCollectionFactory = $ingredientCollectionFactory;
        parent::__construct($context, $connectionName);
    }

    public function _construct()
    {
        $this->_init(self::RECIPE_TABLE, 'entity_id');
    }

    /**
     * @param AbstractModel $object
     * @return $this|AbstractDb
     * @throws Exception
     */
    public function save(AbstractModel $object)
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            parent::save($object);
            /** @var RecipeModel $object */
            $this->saveIngredientsLinks($object);
            $this->saveSteps($object);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            throw $e;
        }

        return $this;
    }

    /**
     * @param RecipeModel $recipe
     */
    private function saveIngredientsLinks(RecipeModel $recipe)
    {
        $connection = $this->getConnection();
        $connection->delete(
            $connection->getTableName(self::RECIPE_INGREDIENT_TABLE),
            $connection->quoteInto(self::RECIPE_ID_FIELD . ' = ?', $recipe->getEntityId())
        );

        $links = [];
        if (!empty($recipe->getIngredients())) {
            foreach ($recipe->getIngredients() as $ingredient) {
                $links[] = [
                    self::RECIPE_ID_FIELD => $recipe->getEntityId(),
                    self::INGREDIENT_ID_FIELD => $ingredient->getEntityId()
                ];
            }

            if (!empty($links)) {
                $connection->insertMultiple(
                    $connection->getTableName(self::RECIPE_INGREDIENT_TABLE),
                    $links
                );
            }
        }
    }

    private function saveSteps(RecipeModel $recipe)
    {
        if (!empty($recipe->getSteps())) {
            foreach ($recipe->getSteps() as $step) {
                $this->stepResource->save($step);
            }
        }
    }

    public function load(AbstractModel $object, $value, $field = null)
    {
        parent::load($object, $value, $field);
        /** @var RecipeModel $object */
        $this->loadIngredientLinks($object);
        $this->loadSteps($object);
        return $this;
    }

    /**
     * @param RecipeModel $recipe
     */
    private function loadIngredientLinks(RecipeModel $recipe)
    {
        $ingredientsLinks = $this->getConnection()->fetchCol(
            'SELECT ' . self::INGREDIENT_ID_FIELD . ' FROM ' . self::RECIPE_INGREDIENT_TABLE . ' WHERE ' . self::RECIPE_ID_FIELD . '=' . $recipe->getEntityId()
        );

        $ingredients = [];
        if (!empty($ingredientsLinks)) {
            $ingredientCollection = $this->ingredientCollectionFactory->create();
            $ingredientCollection->addFieldToFilter('entity_id', ['in' => $ingredientsLinks]);
            $ingredients = $ingredientCollection->getItems();
        }
        $recipe->setIngredients($ingredients);
    }

    private function loadSteps(RecipeModel $recipe)
    {
        $stepCollection = $this->stepCollectionFactory->create();
        $stepCollection->addFieldToFilter(self::RECIPE_ID_FIELD, ['eq' => $recipe->getEntityId()]);
        $recipe->setSteps($stepCollection->getItems());
    }
}

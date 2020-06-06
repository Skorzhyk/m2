<?php

namespace Skorzhyk\Recipe\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Recipe
 * @package Skorzhyk\Recipe\Model\ResourceModel
 */
class Ingredient extends AbstractDb
{
    /**
     * @var string
     */
    const INGREDIENT_TABLE = 'skorzhyk_ingredient';

    /**
     * Recipe constructor.
     * @param Context $context
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public function _construct()
    {
        $this->_init(self::INGREDIENT_TABLE, 'entity_id');
    }

//    /**
//     * @inheritdoc
//     */
//    public function save(AbstractModel $object)
//    {
//        $connection = $this->getConnection();
//        $connection->beginTransaction();
//        try {
//            parent::save($object);
//            /** @var RecipeModel $object */
//            $this->recipeStoreLinkManagement->saveStoreLinksByRecipe($object);
//            $connection->commit();
//        } catch (Exception $e) {
//            $connection->rollBack();
//            throw $e;
//        }
//        return $this;
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function load(AbstractModel $object, $value, $field = null)
//    {
//        parent::load($object, $value, $field);
//        /** @var RecipeModel $object */
//        $this->recipeStoreLinkManagement->loadStoreLinksByRecipe($object);
//        return $this;
//    }
}

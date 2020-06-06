<?php

namespace Skorzhyk\Recipe\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Skorzhyk\Recipe\Model\RecipeFactory;
use Skorzhyk\Recipe\Model\IngredientFactory;
use Skorzhyk\Recipe\Model\Recipe\StepFactory;

class Index extends Action
{
    protected $recipeFactory;

    protected $ingredientFactory;

    protected $stepFactory;

    public function __construct(Context $context, RecipeFactory $recipeFactory, IngredientFactory $ingredientFactory, StepFactory $stepFactory)
    {
        $this->recipeFactory = $recipeFactory;
        $this->ingredientFactory = $ingredientFactory;
        $this->stepFactory = $stepFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $recipe = $this->recipeFactory->create();

        $recipe->getResource()->load($recipe, 2);

        $steps = [];
        for ($i = 1; $i <= 5; $i++) {
            $step = $this->stepFactory->create();
            $step->setRecipeId($recipe->getEntityId());
            $step->setOrderNumber($i);
            $step->setDescription('Step #' . $i);

            $steps[] = $step;
        }
        $recipe->setSteps($steps);

//        $recipe->setTitle('Plov');
//        $recipe->setDescription('Plov Desc');

        $recipe->getResource()->save($recipe);

        $x = 11;
    }
}

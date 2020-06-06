<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Block;

use Fidesio\ChefInfo\Model\ChefInfoEntity;
use Fidesio\Customer\Model\Customer;
use Fidesio\Recipe\Model\Config\Source\RecipeConfig;
use Fidesio\Recipe\Model\Recipe as RecipeModel;
use Fidesio\Recipe\Model\RecipeEntity;
use Fidesio\Recipe\Model\RecipeRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

/**
 * Class Recipe
 * @package Fidesio\Product\Block
 */
class Recipe extends Template
{
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ChefInfoEntity
     */
    private $chefInfoEntity;

    /**
     * @var RecipeEntity
     */
    private $recipeEntity;

    /**
     * @var CollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * @var Customer
     */
    private $customerModel;

    /**
     * Recipe constructor.
     * @param Template\Context $context
     * @param RecipeRepository $recipeRepository
     * @param CustomerSession $customerSession
     * @param ChefInfoEntity $chefInfoEntity
     * @param RecipeEntity $recipeEntity
     * @param CollectionFactory $orderCollectionFactory
     * @param Customer $customerModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        RecipeRepository $recipeRepository,
        CustomerSession $customerSession,
        ChefInfoEntity $chefInfoEntity,
        RecipeEntity $recipeEntity,
        CollectionFactory $orderCollectionFactory,
        Customer $customerModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->recipeRepository = $recipeRepository;
        $this->customerSession = $customerSession;
        $this->chefInfoEntity = $chefInfoEntity;
        $this->recipeEntity = $recipeEntity;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerModel = $customerModel;
    }

    /**
     * @return array
     */
    public function getRecipes()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [];
        }

        $customer = $this->customerSession->getCustomer();
        $recipes = $this->customerModel->isChef($customer) ?
            $this->getChefRecipes($customer) :
            $this->getRecipesFromOrders($customer->getId());

        return $recipes;
    }

    /**
     * @return bool
     */
    public function isCustomerChef()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }
        $customer = $this->customerSession->getCustomer();
        return $this->customerModel->isChef($customer);
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @return array|DataObject[]
     */
    public function getChefRecipes($customer)
    {
        $recipes = [];
        if ($this->customerModel->isChef($customer)) {
            $recipes = $this->recipeRepository->getListByChefId($customer->getId());
        }

        return $recipes;
    }

    /**
     * @param mixed $customerId
     * @return DataObject[]
     */
    public function getRecipesFromOrders($customerId)
    {
        $recipes = [];
        $source = [];

        $orders = $this->getOrdersByCustomerId($customerId);

        foreach ($orders as $order) {
            $orderItems = $order->getAllItems();
            foreach ($orderItems as $orderItem) {
                $recipeInfo = $orderItem->getProduct()->getFidesioRecipe();
                if ($recipeInfo) {
                    array_push($source, explode(',', $recipeInfo));
                }
            }
        }

        if ($source) {
            $recipeIds = array_unique(array_filter(array_merge(...$source)));
            $recipes = $this->recipeRepository->getListByIds($recipeIds);
        }

        return $recipes;
    }

    /**
     * @param mixed $customerId
     * @return OrderInterface[]
     */
    public function getOrdersByCustomerId($customerId)
    {
        $collection = $this->orderCollectionFactory->create();
        $collection->addAttributeToFilter('customer_id', $customerId);

        return $collection->getItems();
    }

    /**
     * Get Recipe save URL.
     *
     * @return string
     */
    public function getSaveUrl(): string
    {
        return $this->getUrl(RecipeConfig::RECIPE_SAVE_URL);
    }

    /**
     * Get Recipe File Url.
     *
     * @param RecipeModel $recipe
     * @return string
     */
    public function getFileUrl($recipe): string
    {
        return $this->recipeEntity->getRecipeFileUrl($recipe->getFile());
    }

    /**
     * Get url for remove Recipe.
     *
     * @param RecipeModel $recipe
     * @return string
     */
    public function getRemoveUrl($recipe): string
    {
        $url = $this->getUrl(
            RecipeConfig::RECIPE_REMOVE_URL,
            [
                '_query' => [
                    'recipe_id' => $recipe->getRecipeId()
                ]
            ]
        );

        return $url;
    }
}

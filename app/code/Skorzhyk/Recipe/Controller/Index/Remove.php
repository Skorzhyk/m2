<?php

namespace Fidesio\Recipe\Controller\Index;

use Exception;
use Fidesio\ChefInfo\Model\ChefInfoEntity;
use Fidesio\Recipe\Model\RecipeRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class Remove
 * @package Fidesio\Recipe\Controller\Index
 */
class Remove extends Action
{
    /**
     * @var RecipeRepository
     */
    private $repository;
    /**
     * @var CustomerSession
     */
    private $customerSession;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ChefInfoEntity
     */
    private $chefInfoEntity;

    /**
     * Remove constructor.
     * @param Context $context
     * @param RecipeRepository $repository
     * @param CustomerSession $customerSession
     * @param ChefInfoEntity $chefInfoEntity
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        RecipeRepository $repository,
        CustomerSession $customerSession,
        ChefInfoEntity $chefInfoEntity,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->customerSession = $customerSession;
        $this->chefInfoEntity = $chefInfoEntity;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $recipeId = $this->getRequest()->getParam('recipe_id');
        $customerId = $this->customerSession->getCustomerId();
        $chef = $this->chefInfoEntity->getLastRecord($customerId);

        if (!$chef instanceof DataObject) {
            throw new CouldNotDeleteException(__('Cannot remove Recipe with id "%1".', $recipeId));
        }

        try {
            $recipe = $this->repository->getById($recipeId);
            if ($recipe->getCustomerId() != $customerId) {
                throw new CouldNotDeleteException(__('Cannot remove Recipe with id "%1".', $recipeId));
            }
        } catch (NoSuchEntityException $exception) {
            $this->logger->critical($exception->getMessage());
        }

        try {
            $this->repository->deleteById($recipeId);
            $this->messageManager->addSuccessMessage(__('Recipe has been successfully removed'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Cannot remove Recipe.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('recipe/customer');
    }
}

<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Controller\Adminhtml\Info;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;
use Fidesio\Recipe\Model\RecipeFactory;
use Fidesio\Recipe\Model\RecipeEntity;
use Fidesio\Recipe\Model\MoveFile;
use Exception;

/**
 * Class Create
 * @package Fidesio\Recipe\Controller\Adminhtml\Info
 */
class Create extends Action
{
    /**
     * @var RedirectFactory
     */
    private $resultRedirect;

    /**
     * @var RecipeFactory
     */
    private $recipeFactory;

    /**
     * @var RecipeEntity
     */
    private $recipeEntity;

    /**
     * @var MoveFile
     */
    private $moveFile;

    /**
     * Create constructor.
     * @param Context $context
     * @param RedirectFactory $resultRedirect
     * @param RecipeFactory $recipeFactory
     * @param RecipeEntity $recipeEntity
     * @param MoveFile $moveFile
     */
    public function __construct(
        Context $context,
        RedirectFactory $resultRedirect,
        RecipeFactory $recipeFactory,
        RecipeEntity $recipeEntity,
        MoveFile $moveFile
    ) {
        $this->recipeFactory = $recipeFactory;
        $this->recipeEntity = $recipeEntity;
        $this->resultRedirect = $resultRedirect;
        $this->moveFile = $moveFile;
        parent::__construct($context);
    }

    /**
     * Save recipe information form action.
     *
     * @return Redirect
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $resultRedirect = $this->resultRedirect->create();

        try {
            $recipeInfo = $this->getRequest()->getParams();
            $recipeInfo['file_name']  = $this->recipeEntity->getFileName($recipeInfo);
            $this->moveFile->moveFile($recipeInfo);
            $model = $this->recipeFactory->create();
            $model->saveRecipeFromAdmin($recipeInfo);
            $this->messageManager->addSuccessMessage(__('The recipe information has been saved.'));
            $resultRedirect->setPath('*/*', ['store' => $storeId]);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath($this->_redirect->getRefererUrl(), ['store' => $storeId]);
        }

        return $resultRedirect;
    }

    /**
     * Check Edit Post Permission.
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Fidesio_Base::activate');
    }
}

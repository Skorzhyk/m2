<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Controller\Adminhtml\Info;

use Fidesio\Recipe\Model\RecipeFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package Fidesio\Recipe\Controller\Adminhtml\Info
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var RecipeFactory
     */
    private $recipeFactory;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param RecipeFactory $recipeFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RecipeFactory $recipeFactory
    ) {
        parent::__construct($context);
        $this->recipeFactory = $recipeFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Create and edit item form action
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Fidesio_Recipe::recipe_info');
        $resultPage->getConfig()->getTitle()->prepend(__('Add Recipe Information'));

        if ($this->getRequest()->getParam('id')) {
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Recipe Information'));
        }

        return $resultPage;
    }

    /**
     * Check Edit Permission.
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Fidesio_Base::activate');
    }
}

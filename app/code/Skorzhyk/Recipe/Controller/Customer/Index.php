<?php

namespace Skorzhyk\Recipe\Controller\Customer;

use Skorzhyk\ChefInfo\Model\ChefInfoEntity;
use Skorzhyk\Customer\Model\Customer;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * Class Index
 * @package Skorzhyk\Recipe\Controller\Customer
 */
class Index extends Action
{
    const CHEF_INFO_ROUTE = 'chef/info/';

    const WARNING_MESSAGE = 'Please fill in chef info';
    /**
     * @var Customer
     */
    private $customerModel;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ChefInfoEntity
     */
    private $chefInfoEntity;

    /**
     * @var UrlInterface
     */
    private $urlInterface;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Index constructor.
     * @param Context $context
     * @param ChefInfoEntity $chefInfoEntity
     * @param Customer $customerModel
     * @param CustomerSession $customerSession
     * @param UrlInterface $urlInterface
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        ChefInfoEntity $chefInfoEntity,
        Customer $customerModel,
        CustomerSession $customerSession,
        UrlInterface $urlInterface,
        ManagerInterface $messageManager
    ) {
        $this->chefInfoEntity = $chefInfoEntity;
        $this->customerModel = $customerModel;
        $this->customerSession = $customerSession;
        $this->urlInterface = $urlInterface;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    /**
     * @return void|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
            $this->customerSession->authenticate();
        }

        $customer = $this->customerSession->getCustomer();
        $chef = $this->chefInfoEntity->getLastRecord($customer->getId());

        if (!$chef && $this->customerModel->isChef($customer)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath(self::CHEF_INFO_ROUTE);
            $this->messageManager->addWarningMessage(self::WARNING_MESSAGE);
            return $resultRedirect;
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}

<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Controller\Adminhtml\Info;

use Exception;
use Skorzhyk\Recipe\Model\Recipe;
use Skorzhyk\Recipe\Model\ResourceModel\Recipe\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package Skorzhyk\Recipe\Controller\Adminhtml\Info
 */
class MassDelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Recipe
     */
    private $model;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Recipe $model
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        Recipe $model
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->model = $model;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $recipesDeleted = 0;
            foreach ($collection->getItems() as $item) {
                $this->model->deleteItem($item);
                $recipesDeleted++;
            }

            if ($recipesDeleted) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 recipe(s) have been deleted.', $recipesDeleted)
                );
            }
        } catch (LocalizedException | Exception $exception) {
            $this->messageManager->addErrorMessage(
                __('We could not delete these recipes')
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }

    /**
     * Check Delete Permission.
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed()
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Controller\Index;

use Exception;
use Skorzhyk\ChefInfo\Model\ChefInfoEntity;
use Skorzhyk\Recipe\Model\MoveFile;
use Skorzhyk\Recipe\Model\RecipeFactory;
use Magento\Catalog\Model\ImageUploader;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\Store;

/**
 * Class Save
 * @package Skorzhyk\Recipe\Controller\Index
 */
class Save extends Action
{
    /**
     * @var int
     */
    const FILE_SIZE_IS_ZERO = 0;

    /**
     * @var ImageUploader
     */
    private $fileUploader;

    /**
     * @var ResultFactory
     */
    private $resultRedirect;

    /**
     * @var RecipeFactory
     */
    private $recipeFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var MoveFile
     */
    private $moveFile;
    /**
     * @var ChefInfoEntity
     */
    private $chefInfoEntity;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param ImageUploader $fileUploader
     * @param ResultFactory $resultRedirect
     * @param RecipeFactory $recipeFactory
     * @param CustomerSession $customerSession
     * @param ChefInfoEntity $chefInfoEntity
     * @param MoveFile $moveFile
     */
    public function __construct(
        Context $context,
        ImageUploader $fileUploader,
        ResultFactory $resultRedirect,
        RecipeFactory $recipeFactory,
        CustomerSession $customerSession,
        ChefInfoEntity $chefInfoEntity,
        MoveFile $moveFile
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
        $this->resultRedirect = $resultRedirect;
        $this->recipeFactory = $recipeFactory;
        $this->customerSession = $customerSession;
        $this->moveFile = $moveFile;
        $this->chefInfoEntity = $chefInfoEntity;
    }

    /**
     * Save new recipe information action.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $customerId = $this->customerSession->getCustomerId();

            $params = $this->getRequest()->getParams();
            $file = $this->getRequest()->getFiles('file');

            $fileName = $file['name'] ?? '';
            $params['title'] = substr($fileName, 0, (strrpos($fileName, ".")));

            if ($file['size'] === self::FILE_SIZE_IS_ZERO) {
                throw new LocalizedException('The file provided is empty');
            }

            $result = $this->fileUploader->saveFileToTmpDir($file);
            $fileName = $result['file'];

            $fileInfo[]['name'] = $fileName;
            $params['file'] = $fileInfo;
            $params['file_name'] = $fileName;
            $params['customer_id'] = $customerId;
            $params['store_id'][] = Store::DEFAULT_STORE_ID;

            $model = $this->recipeFactory->create();
            $params['file_name'] = $this->moveFile->moveFile($params);

            $model->saveRecipeFromMyAccount($params);
            $this->messageManager->addSuccessMessage(__('You saved the recipe information.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t save the recipe information. %1', $e->getMessage()));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('recipe/customer');
    }
}

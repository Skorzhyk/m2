<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Controller\Adminhtml\File;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Upload
 * @package Skorzhyk\Recipe\Controller\Adminhtml\File
 */
class Upload extends Action
{
    /**
     * ImageUploader
     */
    private $fileUploader;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param ImageUploader $fileUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->fileUploader->saveFileToTmpDir('file');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * Upload Permissions
     *
     * @return bool
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _isAllowed()
    {
        return true;
    }
}

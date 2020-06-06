<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Model;

use Fidesio\Recipe\Model\Config\Source\RecipeConfig;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class RecipeEntity
 * @package Fidesio\Recipe\Model
 */
class RecipeEntity extends DataObject
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RecipeEntity constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($data);
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * Get file name.
     *
     * @param array $recipeInfo
     * @return string
     */
    public function getFileName($recipeInfo): string
    {
        $fileName = '';
        $file = $recipeInfo['file'] ?? [];
        if (is_array($file) && !empty($file)) {
            $file = array_pop($file);
            $fileName = $file['name'] ?? '';
        }

        return $fileName;
    }

    /**
     * Get recipe file url.
     *
     * @param string $recipeFile
     * @return string $filePath
     */
    public function getRecipeFileUrl($recipeFile): string
    {
        $filePath = '';
        try {
            $mediaRelativePath = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $filePath = $mediaRelativePath . RecipeConfig::MEDIA_RECIPE_FOLDER . '/' . $recipeFile;
        } catch (NoSuchEntityException $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return $filePath;
    }
}

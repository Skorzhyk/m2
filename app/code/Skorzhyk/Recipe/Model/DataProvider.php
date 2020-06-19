<?php

declare(strict_types=1);

namespace Skorzhyk\Recipe\Model;

use Skorzhyk\Recipe\Model\ResourceModel\Recipe\CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 * @package Skorzhyk\Recipe\Model
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @var RecipeStoreLinkManagement
     */
    private $recipeStoreLinkManagement;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param FileInfo $fileInfo
     * @param RecipeStoreLinkManagement $recipeStoreLinkManagement
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        FileInfo $fileInfo,
        RecipeStoreLinkManagement $recipeStoreLinkManagement,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->fileInfo = $fileInfo;
        $this->recipeStoreLinkManagement = $recipeStoreLinkManagement;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get recipe information collection.
     *
     * @return array
     * @throws FileSystemException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $recipes = $this->collection->getItems();
        $this->loadedData = [];
        foreach ($recipes as $recipe) {
            $recipe = $this->prepareFileData($recipe);
            $this->prepareStoresData($recipe);
            $this->loadedData[$recipe->getId()] = $recipe->getData();
        }

        return $this->loadedData;
    }

    /**
     * Prepare recipe's file data.
     *
     * @param DataObject $recipe
     * @return mixed
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    private function prepareFileData($recipe)
    {
        $fileName = $recipe->getFile();
        $file = [];

        if (!empty($fileName) && $this->fileInfo->isExist($fileName)) {
            $stat = $this->fileInfo->getStat($fileName);
            $mime = $this->fileInfo->getMimeType($fileName);
            $file[0]['name'] = $fileName;
            $file[0]['url'] = $this->fileInfo->getFileUrl($fileName);
            $file[0]['size'] = isset($stat) ? $stat['size'] : 0;
            $file[0]['type'] = $mime;
        }
        $recipe->setFile($file);

        return $recipe;
    }

    /**
     * @param Recipe $recipe
     * @return void
     */
    private function prepareStoresData($recipe)
    {
        $this->recipeStoreLinkManagement->loadStoreLinksByRecipe($recipe);
        $storeLinks = $recipe->getStoreLinks();
        $storeIds = [];
        foreach ($storeLinks as $storeLink) {
            $storeIds[] = (string)$storeLink->getStoreId();
        }
        $recipe->setStoreId($storeIds);
    }
}

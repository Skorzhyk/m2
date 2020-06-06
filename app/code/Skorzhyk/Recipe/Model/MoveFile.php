<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Model;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Fidesio\Recipe\Model\Config\Source\RecipeConfig;

/**
 * Class MoveFile
 * @package Fidesio\Recipe\Model
 */
class MoveFile
{
    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @var ImageUploader
     */
    private $fileUploader;

    /**
     * @var RecipeEntity
     */
    private $recipeEntity;

    /**
     * FileInfo constructor.
     *
     * @param FileInfo $fileInfo
     * @param ImageUploader $fileUploader
     * @param RecipeEntity $recipeEntity
     */
    public function __construct(
        FileInfo $fileInfo,
        ImageUploader $fileUploader,
        RecipeEntity $recipeEntity
    ) {
        $this->fileInfo = $fileInfo;
        $this->fileUploader = $fileUploader;
        $this->recipeEntity = $recipeEntity;
    }

    /**
     * @param array $recipeInformation
     * @return string|false
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function moveFile($recipeInformation)
    {
        $fileName = $recipeInformation['file_name'];
        if ($this->fileInfo->isExist($fileName, RecipeConfig::MEDIA_RECIPE_TMP_FOLDER)) {
            return $this->fileUploader->moveFileFromTmp($fileName);
        }
        return false;
    }
}

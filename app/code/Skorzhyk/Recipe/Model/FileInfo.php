<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\FileSystemException;
use Fidesio\Recipe\Model\Config\Source\RecipeConfig;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class FileInfo
 *
 * @package Fidesio\Recipe\Model
 */
class FileInfo
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * FileInfo constructor.
     *
     * @param Filesystem $filesystem
     * @param Mime $mime
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Filesystem $filesystem,
        Mime $mime,
        StoreManagerInterface $storeManager
    ) {
        $this->filesystem = $filesystem;
        $this->mime = $mime;
        $this->storeManager = $storeManager;
    }

    /**
     * @return WriteInterface
     * @throws FileSystemException
     */
    private function getMediaDirectory(): WriteInterface
    {
        if ($this->mediaDirectory === null) {
            $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        }

        return $this->mediaDirectory;
    }

    /**
     * @param string $fileName
     * @return string
     * @throws FileSystemException
     */
    public function getMimeType($fileName): string
    {
        $filePath = RecipeConfig::MEDIA_RECIPE_FOLDER . '/' . ltrim($fileName, '/');
        $absoluteFilePath = $this->getMediaDirectory()->getAbsolutePath($filePath);
        $result = $this->mime->getMimeType($absoluteFilePath);

        return $result;
    }

    /**
     * @param string $fileName
     * @return array
     * @throws FileSystemException
     */
    public function getStat($fileName): array
    {
        $filePath = RecipeConfig::MEDIA_RECIPE_FOLDER . '/' . ltrim($fileName, '/');
        $result = $this->getMediaDirectory()->stat($filePath);

        return $result;
    }

    /**
     * @param string $fileName
     * @param null|string $baseTmpPath
     * @return bool
     * @throws FileSystemException
     */
    public function isExist($fileName, $baseTmpPath = null): bool
    {
        $filePath = RecipeConfig::MEDIA_RECIPE_FOLDER . '/' . ltrim($fileName, '/');
        if ($baseTmpPath) {
            $filePath = $baseTmpPath . '/' . ltrim($fileName, '/');
        }

        return $this->getMediaDirectory()->isExist($filePath);
    }

    /**
     * @param string $fileName
     * @param null|string $baseTmpPath
     * @return bool
     * @throws FileSystemException
     */
    public function deleteFile($fileName, $baseTmpPath = null): bool
    {
        $filePath = RecipeConfig::MEDIA_RECIPE_FOLDER . '/' . ltrim($fileName, '/');
        if ($baseTmpPath) {
            $filePath = $baseTmpPath . '/' . ltrim($fileName, '/');
        }

        return $this->getMediaDirectory()->delete($filePath);
    }

    /**
     * @param string $fileName
     * @return string
     * @throws NoSuchEntityException
     */
    public function getFileUrl($fileName): string
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl . RecipeConfig::MEDIA_RECIPE_FOLDER . '/' . $fileName;
    }
}

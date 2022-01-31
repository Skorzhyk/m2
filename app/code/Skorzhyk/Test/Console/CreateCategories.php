<?php
declare(strict_types=1);

namespace Skorzhyk\Test\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterfaceFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class CreateCategories extends Command
{
    private const FILE_PATH = 'csv/сategories.csv';

    private const DELIMITER = '|';

    private const ROOT_CATEGORY_ID = 1;

    /** @var Csv */
    private $csvProcessor;

    /** @var Filesystem */
    private $filesystem;

    /** @var CategoryInterfaceFactory */
    private $categoryFactory;

    /** @var CategoryRepositoryInterface */
    private $categoryRepository;

    /** @var OutputInterface */
    private $output;

    /**
     * @param Csv $csvProcessor
     * @param Filesystem $filesystem
     * @param CategoryInterfaceFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param string|null $name
     */
    public function __construct(
        Csv $csvProcessor,
        Filesystem $filesystem,
        CategoryInterfaceFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        string $name = null
    ) {
        $this->csvProcessor = $csvProcessor;
        $this->filesystem = $filesystem;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('skorzhyk:create-categories');
        $this->setDescription('Create categories tree from CSV file.');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $varDir = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $filePath = $varDir->getAbsolutePath() . self::FILE_PATH;

        if (file_exists($filePath)) {
            try {
                $rawData = $this->csvProcessor->getData($filePath);
                $this->createTree($rawData);
            } catch (\Exception $e) {
                echo 'Shit happened!';
                echo $e->getMessage();
                echo '<br/>';
            }
        }
    }

    private function createTree(array $rawData): void
    {
        $categories = [];

        foreach ($rawData as $row) {
            $stringPath = array_shift($row);
            if ($stringPath !== null && strpos($stringPath, self::DELIMITER)) {
                $path = explode(self::DELIMITER, $stringPath);

                $categories = $this->addCategory($categories, $path);
            }
        }

        $initialCategory = array_key_first($categories);
        $this->createCategory($initialCategory, $categories[$initialCategory]);
    }

    private function addCategory(array $destination, array $path): array
    {
        $category = array_shift($path);

        if ($category !== null) {
            if (!array_key_exists($category, $destination)) {
                $destination[$category] = [];
            }

            $destination[$category] = $this->addCategory($destination[$category], $path);
        }

        return $destination;
    }

    private function createCategory(
        string $name,
        array $children,
        int $parentId = self::ROOT_CATEGORY_ID
    ) {
        $category = $this->categoryFactory->create();
        $category->setName($name);
        $category->setParentId($parentId);
        $category->setIsActive(1);

        try {
            $categoryId = $this->categoryRepository->save($category)->getId();

            $this->output->writeln('Category "' . $name . '" has been created with ID = ' . $categoryId);

            if (count($children) > 0) {
                foreach ($children as $name => $grandChildren) {
                    $this->createCategory($name, $grandChildren, (int)$categoryId);
                }
            }
        } catch (\Exception $e) {
            $this->output->writeln('Shit happened!');
            $this->output->writeln($e->getMessage());
        }
    }
}

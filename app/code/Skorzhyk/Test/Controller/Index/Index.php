<?php
declare(strict_types=1);

namespace Skorzhyk\Test\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class Index extends Action
{
    private $productCollection;

    public function __construct(Context $context, ProductCollection $productCollection)
    {
        $this->productCollection = $productCollection;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $product = $this->productCollection
            ->addAttributeToSelect('*')
            ->addFieldToSelect('*')
            ->addFieldToFilter('entity_id', ['eq' => 1])
            ->getFirstItem();

        $layout = $product->getLayout();

        echo 'Mage!';
    }
}

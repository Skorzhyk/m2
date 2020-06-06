<?php

declare(strict_types=1);

namespace Fidesio\Recipe\Ui\Component\Recipe;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\AbstractComponent;

/**
 * Class MassAction
 * @package Fidesio\Recipe\Ui\Component\Recipe
 */
class MassAction extends AbstractComponent
{
    const NAME = 'massaction';

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * Constructor
     *
     * @param AuthorizationInterface $authorization
     * @param ContextInterface $context
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        AuthorizationInterface $authorization,
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        $this->authorization = $authorization;
        parent::__construct($context, $components, $data);
    }

    /**
     * @return void
     */
    public function prepare(): void
    {
        $config = $this->getConfiguration();

        foreach ($this->getChildComponents() as $actionComponent) {
            $actionType = $actionComponent->getConfiguration()['type'];
            if ($this->isActionAllowed($actionType)) {
                $config['actions'][] = $actionComponent->getConfiguration();
            }
        }
        $origConfig = $this->getConfiguration();
        if ($origConfig !== $config) {
            $config = array_replace_recursive($config, $origConfig);
        }

        $this->setData('config', $config);
        $this->components = [];

        parent::prepare();
    }

    /**
     * Check if the given type of action is allowed
     *
     * @param string $actionType
     * @return bool
     */
    public function isActionAllowed($actionType): bool
    {
        $isAllowed = true;
        switch ($actionType) {
            case 'status':
            case 'delete':
                $isAllowed = $this->authorization->isAllowed('Fidesio_Base::activate');
                break;
            default:
                break;
        }
        return $isAllowed;
    }

    /**
     * @return string
     */
    public function getComponentName(): string
    {
        return static::NAME;
    }
}

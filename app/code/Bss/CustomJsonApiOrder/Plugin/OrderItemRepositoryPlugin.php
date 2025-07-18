<?php

namespace Bss\CustomJsonApiOrder\Plugin;

use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemExtensionInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;

class OrderItemRepositoryPlugin
{
    const CUSTOM_ATTR_NAME = 'custom_option_price';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderItemExtensionFactory
     */
    protected $extensionFactory;

    /**
     * OrderItemRepositoryPlugin constructor
     *
     * @param OrderItemExtensionFactory $extensionFactory
     */
    public function __construct(OrderItemExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     *
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemInterface $orderItem
     *
     * @return OrderItemInterface
     */
    public function afterGet(OrderItemRepositoryInterface $subject, OrderItemInterface $orderItem)
    {

        $customAttrName = $orderItem->getData(self::CUSTOM_ATTR_NAME)? $orderItem->getData(self::CUSTOM_ATTR_NAME): 0;
        $extensionAttributes = $orderItem->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

        $extensionAttributes->setCustomOptionPrice($customAttrName);
        $orderItem->setExtensionAttributes($extensionAttributes);

        return $orderItem;
    }

    /**
     *
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemSearchResultInterface $searchResult
     *
     * @return OrderItemSearchResultInterface
     */
    public function afterGetList(OrderItemRepositoryInterface $subject, OrderItemSearchResultInterface $searchResult)
    {
        $orderItems = $searchResult->getItems();

        foreach ($orderItems as &$item) {

            $customAttrName = $item->getData(self::CUSTOM_ATTR_NAME)? $item->getData(self::CUSTOM_ATTR_NAME) : 0;
            $extensionAttributes = $item->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

            $extensionAttributes->setCustomOptionPrice($customAttrName);
            $item->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}

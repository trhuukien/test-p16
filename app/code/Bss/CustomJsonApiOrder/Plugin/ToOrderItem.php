<?php
namespace Bss\CustomJsonApiOrder\Plugin;

class ToOrderItem
{
    /**
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem $subject
     * @param callable $proceed
     * @param $quoteItem
     * @param $data
     * @return mixed
     */
    public function aroundConvert(\Magento\Quote\Model\Quote\Item\ToOrderItem $subject, callable $proceed, $quoteItem, $data)
    {
        $orderItem = $proceed($quoteItem, $data);

        $custom_option_price = $quoteItem->getCustomOptionPrice();

        $orderItem->setCustomOptionPrice($custom_option_price);

        return $orderItem;
    }
}

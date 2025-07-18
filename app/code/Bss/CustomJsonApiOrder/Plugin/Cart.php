<?php
namespace Bss\CustomJsonApiOrder\Plugin;

class Cart
{
    /**
     * @param \Magento\Quote\Model\Quote\Item\Processor $subject
     * @param $result
     * @param $item
     * @param $request
     * @param $candidate
     * @return mixed
     */
    public function afterPrepare(\Magento\Quote\Model\Quote\Item\Processor $subject, $result, $item, $request, $candidate)
    {
        $product = $candidate;
        $customOptionPrice = 0.00;
        $optionIds = $product->getCustomOption('option_ids');
        if ($optionIds) {
            $basePrice = $product->getPriceModel()->getBasePrice($product, null);
            foreach (explode(',', $optionIds->getValue()) as $optionId) {
                if ($option = $product->getOptionById($optionId)) {
                    $confItemOption = $product->getCustomOption('option_' . $option->getId());

                    $group = $option->groupFactory($option->getType())
                        ->setOption($option)
                        ->setConfigurationItemOption($confItemOption);
                    $customOptionPrice += $group->getOptionPrice($confItemOption->getValue(), $basePrice);
                }
            }
        }
        $item->setCustomOptionPrice($customOptionPrice);
        return $result;
    }
}


<?php
/**
 * Anowave Magento 2 Filter Payment Method
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * http://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_FilterPayment
 * @copyright 	Copyright (c) 2021 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */


namespace Anowave\FilterPayment\Model\Filter;

use Magento\Framework\DataObject;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;
use Anowave\FilterPayment\Model\FilterInterface;

class QuoteContent implements FilterInterface
{
    /**
     * Execute
     *
     * @param MethodInterface $paymentMethod
     * @param CartInterface $quote
     * @param DataObject $result
     * 
     * @return void
     */
    public function execute(MethodInterface $paymentMethod, CartInterface $quote, DataObject $result)
    {
        foreach ($quote->getItems() as $item) 
        {
        	$product = $item->getProduct();

            $customAttribute = $product->getCustomAttribute(\Anowave\FilterPayment\Setup\InstallData::ATTRIBUTE);

            if ($customAttribute === null) 
            {
                continue;
            }
            
            $allowed = $customAttribute->getValue();

            if ($allowed == '') 
            {
                continue;
            }

            $allowed = explode(chr(44), $allowed);
            
            if (in_array($paymentMethod->getCode(), $allowed)) 
            {
                $result->setData('is_available', true);
            }
            else 
            {
                $result->setData('is_available', false);
            }
        }
    }
}
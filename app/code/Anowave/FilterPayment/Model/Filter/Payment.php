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

use Anowave\FilterPayment\Model\FilterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;

class Payment implements FilterInterface
{
    const XML_PATH_ALLOWED_PAYMENT_METHODS = 'checkout/options/allowed_payment_methods';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct
    (
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Execute
     *
     * @param MethodInterface $paymentMethod
     * @param CartInterface $quote
     * @param DataObject $result
     */
    public function execute(MethodInterface $paymentMethod, CartInterface $quote, DataObject $result)
    {	
        $allowed = $this->scopeConfig->getValue(static::XML_PATH_ALLOWED_PAYMENT_METHODS);
        
        if ($allowed === null || $allowed === '') 
        {
            return;
        }

        $allowed = explode(chr(44), $allowed);
        
        $result->setData('is_available', in_array($paymentMethod->getCode(), $allowed));
    }
}
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
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2019 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */

namespace Anowave\FilterPayment\Model\Filter;

use Anowave\FilterPayment\Model\FilterInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;

class Guest implements FilterInterface
{
    const XML_PATH_ALLOWED_PAYMENT_METHODS_FOR_GUEST = 'checkout/options/allowed_payment_methods_for_guest';

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
        $customer = $quote->getCustomer();

        if ($customer && ($customer instanceof CustomerInterface) && $customer->getId()) 
        {
            return;
        }

        $allowed = $this->scopeConfig->getValue(self::XML_PATH_ALLOWED_PAYMENT_METHODS_FOR_GUEST);

        if ($allowed === null || $allowed === '') 
        {
            return;
        }

        $allowed = explode(chr(44), $allowed);
        
        $result->setData('is_available', in_array($paymentMethod->getCode(), $allowed));
    }
}
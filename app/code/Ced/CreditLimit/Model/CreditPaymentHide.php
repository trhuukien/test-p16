<?php
/**
 * CedCommerce
  *
  * NOTICE OF LICENSE
  *
  * This source file is subject to the End User License Agreement (EULA)
  * that is bundled with this package in the file LICENSE.txt.
  * It is also available through the world-wide-web at this URL:
  * http://cedcommerce.com/license-agreement.txt
  *
  * @category  Ced
  * @package   Ced_CreditLimit
  * @author    CedCommerce Core Team <connect@cedcommerce.com >
  * @copyright Copyright CEDCOMMERCE (http://cedcommerce.com/)
  * @license      https://cedcommerce.com/license-agreement.txt
  */
namespace Ced\CreditLimit\Model;
/**
 * Class CreditPaymentHide
 * @package Ced\CreditLimit\Model
 */
class CreditPaymentHide extends \Ced\CreditLimit\Model\CreditPayment 
{
    /**
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$checkoutdata = $objectManager->create('Magento\Checkout\Model\Cart');
    	$customer = $objectManager->create('Magento\Customer\Model\Session');
    	if(!$objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('b2bextension/credit_limit/enable')){
        return false;
      }
      if($customer->isLoggedIn())
    	{
    		$creditdata = $objectManager->create('Ced\CreditLimit\Model\CreditLimit')->load($customer->getCustomerId(),'customer_id');
    		$discountTotal =0;
	    	foreach ($checkoutdata->getQuote()->getAllItems() as $item){
	    		$discountTotal += $item->getDiscountAmount();
	    	}
    		$total = $checkoutdata->getQuote()->getBaseGrandTotal();
    		$paymentamount = $total-$discountTotal;
    		if($creditdata->getRemainingAmount()<$paymentamount)
    			return false;
    		else 
    			return true;
    	}
    	else{
    		return false;
    	}
    }
}
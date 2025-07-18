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
 * Class CreditPayment
 * @package Ced\CreditLimit\Model
 */
class CreditPayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     *
     */
	const METHOD_CODE = 'paybycredit';
    /**
     * @var string
     */
	protected $_code = 'paybycredit';
	
	public $_canUseInternal   = false;
	
    /**
     * @var string
     */
    protected $_formBlockType = \Ced\CreditLimit\Block\Form\CreditPayment::class;
   
}

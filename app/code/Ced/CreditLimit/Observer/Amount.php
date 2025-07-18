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
namespace Ced\CreditLimit\Observer; 
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Symfony\Component\DependencyInjection\Loader\ProtectedPhpFileLoader;

/**
 * Class Amount
 * @package Ced\CreditLimit\Observer
 */
Class Amount implements ObserverInterface
{
    /**
	 * 
	 * @param \Ced\CreditLimit\Model\CreditLimit $creditLimit
	 * @param \Ced\CreditLimit\Model\CreditOrder $creditOrder
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	 * \Magento\Sales\Model\OrderRepository $orderRepository
	 */
    public function __construct(
    	\Ced\CreditLimit\Model\CreditLimitFactory $creditLimit,
    	\Ced\CreditLimit\Model\CreditOrderFactory $creditOrder,
    	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    	\Magento\Sales\Model\OrderRepository $orderRepository
    
    ) {
        $this->scopeConfig = $scopeConfig;
    	$this->creditLimit = $creditLimit;
    	$this->creditOrder = $creditOrder;
    	$this->order = $orderRepository;
    }
    /**
     * Order credit limit update
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {   
        $orderid = $observer->getEvent()->getOrderIds();
        $status = $this->scopeConfig->getValue('payment/paybycredit/order_status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        foreach($orderid as $_orderId){
            $order =$this->order->get($_orderId);
            if($order->getPayment()->getMethodInstance()->getCode()=="paybycredit"){
                
                if(!$order->getCustomerIsGuest()){
    	           	$model = $this->creditLimit->create()->load($order->getCustomerId(),'customer_id');
    	            if($model->getId() && $model->getRemainingAmount() >= $order->getBaseGrandTotal()){
    	                 $model->setUsedAmount($model->getUsedAmount()+$order->getBaseGrandTotal());
    	                 if($status=='pending' || $status=='ced_credit_limit'){
    	                    $model->getPaymentDue()?$model->setPaymentDue($model->getPaymentDue()+ $order->getBaseGrandTotal()):$model->setPaymentDue($order->getBaseGrandTotal());
    	                    $model->setRemainingAmount($model->getRemainingAmount()-$order->getBaseGrandTotal());
    	                 }
    	                 $model->save();
    	                 $this->applyTransaction($order); 	                    
    	            }
                }
            }
        }
        return $this;
    }
    
    /**
     * 
     * @param \Magento\Sales\Mode\Order $order
     */
    protected function applyTransaction($order){
    	
    	$modelCreditOrder = $this->creditOrder->create();
    	$modelCreditOrder->setCustomerId($order->getCustomerId());
    	$modelCreditOrder->setOrderId($order->getId());
    	$modelCreditOrder->setOrderAmount($order->getGrandTotal());
    	$modelCreditOrder->setCreditAmount($order->getGrandTotal());
    	$modelCreditOrder->save();
    	
    }
}
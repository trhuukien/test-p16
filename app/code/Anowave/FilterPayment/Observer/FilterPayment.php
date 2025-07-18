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
 * @copyright 	Copyright (c) 2020 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */
namespace Anowave\FilterPayment\Observer;

use Anowave\FilterPayment\Model\FilterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;

class FilterPayment implements ObserverInterface
{
	/**
	 * @var FilterInterface[] $filterList
	 */
    protected $filterList;
    
    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cart;
    
    /**
     * @var \Anowave\FilterPayment\Helper\Data
     */
    protected $helper;
    
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state = null;

    /**
     * Constructor
     *
     * @param FilterInterface[] $filterList
     * @param \Magento\Checkout\Helper\Cart $cart
     * @param \Anowave\FilterPayment\Helper\Data $helper
     */
    public function __construct
    (
    	$filterList,
    	\Magento\Checkout\Helper\Cart $cart,
    	\Anowave\FilterPayment\Helper\Data $helper, 
    	\Magento\Framework\App\State $state
    )
    {
    	/**
    	 * Set filter list 
    	 * 
    	 * @var FilterInterface[] $filterList
    	 */
        $this->filterList = $filterList;
        
        /**
         * Set cart
         * 
         * @var \Magento\Checkout\Helper\Cart $cart
         */
        $this->cart = $cart;
        
        /**
         * Set helper 
         * 
         * @var \Anowave\FilterPayment\Helper\Data $helper
         */
        $this->helper = $helper;
        
        /**
         * Set state 
         * 
         * @var \Magento\Framework\App\State $state
         */
        $this->state = $state;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
    	if (!$this->helper->isActive())
    	{
    		return;
    	}
    	
    	if ($this->state->getAreaCode() === \Magento\Framework\App\Area::AREA_ADMINHTML)
    	{
    		return;
    	}
    	
        $event = $observer->getEvent();

        if (!$event || !($event instanceof Event)) 
        {
            return;
        }

        $result = $event->getResult();
        
       
        if (!$result || !($result instanceof DataObject) || !$result->getIsAvailable()) 
        {
            return;
        }

        $paymentMethod = $event->getMethodInstance();

        if (!$paymentMethod || !($paymentMethod instanceof MethodInterface)) 
        {
            return;
        }
        
        $quote = $event->getQuote();

        if (!$quote)
        {
           	$quote = $this->cart->getQuote();
           
           	if (!($quote instanceof \Magento\Quote\Model\Quote))
           	{
           		return;
           	}
        }
        
        foreach ($this->filterList as $filter) 
        {
            $filter->execute($paymentMethod, $quote, $result);
        }
    }
}
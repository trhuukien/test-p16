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
 
namespace Anowave\FilterPayment\Plugin;

class Shipping
{
    /**
     * @var  \Magento\Customer\Model\Session\Proxy
     */
    protected $sessionProxy;
    
    
    /**
     * @var \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory
     */
    protected $groupShippingCollectionFactory;
    
    /**
     * Allowed shipping methods
     * 
     * @var array
     */
    private $allowed = [];
    
    /**
     * Constructor 
     * 
     * @param \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory $groupShippingCollectionFactory
     */
    public function __construct
    (
        \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory $groupShippingCollectionFactory,
        \Magento\Customer\Model\Session\Proxy $sessionProxy
    )
    {
        $this->groupShippingCollectionFactory = $groupShippingCollectionFactory;
        
        /**
         * Set session proxy 
         * 
         * @var \Magento\Customer\Model\Session\Proxy $sessionProxy
         */
        $this->sessionProxy = $sessionProxy;
    }
    
    /**
     * Arround carrier rates
     * 
     * @param \Magento\Shipping\Model\Shipping $shipping
     * @param callable $proceed
     * @param string $carrierCode
     * @param unknown $request
     * @return unknown
     */
    public function aroundCollectCarrierRates(\Magento\Shipping\Model\Shipping $shipping, callable $proceed, $carrierCode, \Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $filter = $this->getAllowedShippingMethods();
        
        if (!$filter)
        {
            return $proceed($carrierCode, $request);
        }
        else 
        {
            foreach ($filter as $entity)
            {
                if (in_array($carrierCode, [$entity[0]]))
                {
                    return $proceed($carrierCode, $request);
                }
            }
        }
        
        return [];
    }
    
    /**
     * Get allowed shipping methods
     * 
     * @return array
     */
    private function getAllowedShippingMethods() : array
    {
        if (!$this->allowed)
        {
           
            if ($this->sessionProxy->isLoggedIn())
            {
                try
                {
                    $group_id = (int) $this->sessionProxy->getCustomer()->getGroupId();
                }
                catch (\Exception $e)
                {
                    $group_id = 0;
                }
            }
            else 
            {
                $group_id = 0;
            }

            $collection = $this->groupShippingCollectionFactory->create()->addFieldToFilter('entity_group_id', $group_id);

            if ($collection->getSize())
            {
                foreach ($collection as $entity)
                {
                    $this->allowed[] = explode(\Anowave\FilterPayment\Plugin\Group\Form::SEPARATOR, $entity->getEntityGroupShipping());
                }
            }
        }
        
        return $this->allowed;
    }
}
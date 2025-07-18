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
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DataObject;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;

class Group implements FilterInterface
{
    /**
     * @var \Anowave\FilterPayment\Model\ResourceModel\Group\CollectionFactory
     */
    protected $groupPaymentCollectionFactory;
    
    public function __construct
    (
        \Anowave\FilterPayment\Model\ResourceModel\Group\CollectionFactory $groupPaymentCollectionFactory
    )
    {
        $this->groupPaymentCollectionFactory = $groupPaymentCollectionFactory;
    }
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
        $customer = $quote->getCustomer();
        
        if (!$customer || !($customer instanceof CustomerInterface) || !$customer->getId())
        {
            $group_id = 0;
        }
        else 
        {
            $group_id = $customer->getGroupId();
        }
        
        $collection = $this->groupPaymentCollectionFactory->create()->addFieldToFilter('entity_group_id', $group_id);
        
        if ($collection->getSize())
        {
            $allowed = [];
            
            foreach ($collection as $entity)
            {
                $allowed[] = $entity->getEntityGroupPayment();
            }

            $result->setData('is_available', in_array($paymentMethod->getCode(), $allowed));
        }
        
        return;
    }
}
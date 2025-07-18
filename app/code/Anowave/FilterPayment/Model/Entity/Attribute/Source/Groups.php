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
namespace Anowave\FilterPayment\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Payment\Helper\Data;

class Groups extends AbstractSource
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory 
     */
    protected $groupCollectionFactory;

    /**
     * Constructor 
     * 
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
     */
    public function __construct(\Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory)
    {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        try 
        {
            if ($this->_options === null) 
            {
                $this->_options = $this->groupCollectionFactory->create()->toOptionArray();
            }
        }
        catch (\Exception $e)
        {
            $errors[] = $e->getMessage();
        }
        
        return $this->_options;
    }
}
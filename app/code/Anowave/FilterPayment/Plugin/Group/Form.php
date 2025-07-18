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
 
namespace Anowave\FilterPayment\Plugin\Group;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\GroupFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Registry;

class Form
{
    const SEPARATOR = '_';
    
    /**
     * Payment filter attribute 
     * 
     * @var string
     */
	const FIELD = 'group_payment';
	
	/**
	 * Shipping filter attribute
	 * 
	 * @var string
	 */
	const FIELD_SHIPPING = 'group_shipping';
	
	/**
	 * @var FilterBuilder
	 */
	protected $_filterBuilder;
	
	/**
	 * @var GroupRepositoryInterface
	 */
	protected $_groupRepository;
	
	/**
	 * @var SearchCriteriaBuilder
	 */
	protected $_searchCriteriaBuilder;
	
	/**
	 * @var GroupFactory
	 */
	protected $_groupFactory;
	
	/**
	 * @var Registry
	 */
	protected $_coreRegistry;
	
	/**
	 * @var \Anowave\FilterPayment\Model\Config\Source\Methods
	 */
	protected $methods;
	
	/**
	 * @var \Anowave\FilterPayment\Model\ResourceModel\Group\CollectionFactory
	 */
	protected $groupPaymentCollectionFactory;
	
	/**
	 * @var \Anowave\FilterPayment\Model\GroupFactory
	 */
	protected $groupPaymentFactory;
	
	/**
	 * @var \Magento\Framework\Message\ManagerInterface
	 */
	protected $messageManager;
	
	/**
	 * @var \Magento\Shipping\Model\Config
	 */
	protected $shippingConfig;
	
	/**
	 * @var \Magento\Framework\App\Config\ScopeConfigInterface
	 */
	protected $scopeConfig;
	
	/**
	 * @var \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory
	 */
	protected $groupShippingCollectionFactory;
	
	/**
	 * @var \Anowave\FilterPayment\Model\GroupShippingFactory
	 */
	protected $groupShippingFactory;
	
	/**
	 * Constructor 
	 * 
	 * @param FilterBuilder $filterBuilder
	 * @param GroupRepositoryInterface $groupRepository
	 * @param SearchCriteriaBuilder $searchCriteriaBuilder
	 * @param GroupFactory $groupFactory
	 * @param Registry $registry
	 * @param \Anowave\FilterPayment\Model\Config\Source\Methods $methods
	 * @param \Anowave\FilterPayment\Model\ResourceModel\Group\CollectionFactory $groupPaymentCollectionFactory
	 * @param \Anowave\FilterPayment\Model\GroupFactory $groupPaymentFactory
	 * @param \Magento\Framework\Message\ManagerInterface $messageManager
	 * @param \Magento\Shipping\Model\Config $shippingConfig
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	 * @param \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory $groupShippingCollectionFactory
	 * @param \Anowave\FilterPayment\Model\GroupShippingFactory $groupShippingFactory
	 */
	public function __construct
	(
		FilterBuilder $filterBuilder,
		GroupRepositoryInterface $groupRepository, 
		SearchCriteriaBuilder $searchCriteriaBuilder, 
		GroupFactory $groupFactory,
		Registry $registry,
	    \Anowave\FilterPayment\Model\Config\Source\Methods $methods,
	    \Anowave\FilterPayment\Model\ResourceModel\Group\CollectionFactory $groupPaymentCollectionFactory,
	    \Anowave\FilterPayment\Model\GroupFactory $groupPaymentFactory,
	    \Magento\Framework\Message\ManagerInterface $messageManager,
	    \Magento\Shipping\Model\Config $shippingConfig,
	    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	    \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory $groupShippingCollectionFactory,
	    \Anowave\FilterPayment\Model\GroupShippingFactory $groupShippingFactory
	)
	{
		$this->_filterBuilder = $filterBuilder;
		
		/**
		 * Set group respository 
		 * 
		 * @var GroupRepositoryInterface $_groupRepository
		 */
		$this->_groupRepository = $groupRepository;
		
		/**
		 * Set search criteria builder
		 * 
		 * @var SearchCriteriaBuilder $_searchCriteriaBuilder
		 */
		$this->_searchCriteriaBuilder = $searchCriteriaBuilder;
		
		/**
		 * Set group factory 
		 * 
		 * @var GroupFactory $_groupFactory
		 */
		$this->_groupFactory = $groupFactory;
		
		/**
		 * Set registry 
		 * 
		 * @var Registry $_coreRegistry
		 */
		$this->_coreRegistry = $registry;
		
		/**
		 * Set methods
		 * 
		 * @var \Anowave\FilterPayment\Model\Config\Source\Methods $methods
		 */
		$this->methods = $methods;
		
		/**
		 * Set group payment factory 
		 * 
		 * @var \Anowave\FilterPayment\Model\ResourceModel\Group\CollectionFactory $groupPaymentFactory
		 */
		$this->groupPaymentCollectionFactory = $groupPaymentCollectionFactory;
		
		/**
		 * @var\Anowave\FilterPayment\Model\GroupFactory $groupPaymentFactory
		 */
		$this->groupPaymentFactory = $groupPaymentFactory;
		
		/**
		 * Set message manager 
		 * 
		 * @var \Magento\Framework\Message\ManagerInterface $messageManager
		 */
		$this->messageManager = $messageManager;
		
		/**
		 * Set shipping config 
		 * 
		 * @var \Magento\Shipping\Model\Config $shippingConfig
		 */
		$this->shippingConfig = $shippingConfig;
		
		/**
		 * Set scope config 
		 * 
		 * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
		 */
		$this->scopeConfig = $scopeConfig;
		
		/**
		 * Set group shipping collection factory 
		 * 
		 * @var \Anowave\FilterPayment\Model\ResourceModel\GroupShipping\CollectionFactory  $groupShippingCollectionFactory
		 */
		$this->groupShippingCollectionFactory = $groupShippingCollectionFactory;
		
		/**
		 * Set group shipping factory
		 * 
		 * @var \Anowave\FilterPayment\Model\GroupShippingFactory $groupShippingFactory
		 */
		$this->groupShippingFactory = $groupShippingFactory;
	}    
	
	public function afterSetForm
	(
		\Magento\Customer\Block\Adminhtml\Group\Edit\Form $context
	)
	{
		$form = $context->getForm();
		
		$group_id = (int) $this->_coreRegistry->registry(RegistryConstants::CURRENT_GROUP_ID);
		
		if (!is_null($group_id))
		{
		    $value = [];
		    
		    $collection = $this->groupPaymentCollectionFactory->create()->addFieldToFilter('entity_group_id', $group_id);
		    
		    foreach ($collection as $entity)
		    {
		        $value[] = $entity->getEntityGroupPayment();
		    }
		    
		    $value_shipping = [];
		    
		    $collection_shipping = $this->groupShippingCollectionFactory->create()->addFieldToFilter('entity_group_id', $group_id);
		    
		    foreach ($collection_shipping as $entity)
		    {
		        $value_shipping[] = $entity->getEntityGroupShipping();
		    }
		}
		else 
		{
		    $value = [];
		    $value_shipping = [];
		}

		$fieldset_payment = $form->addFieldset('payment_fieldset', ['legend' => __('Filter payment')]);
		
		$group_tax = $fieldset_payment->addField(static::FIELD, 'multiselect',
		[
			'name' 		=> static::FIELD,
			'label' 	=> __('Allowed payment methods'),
			'title' 	=> __('Allowed payment methods'),
			'required' 	=> false,
			'values' 	=> $this->methods->toOptionArray(),
			'value' => $value
		]);


		$fieldset_shipping = $form->addFieldset('shipping_fieldset', ['legend' => __('Filter shipping methods')]);
		
		$group_tax = $fieldset_shipping->addField(static::FIELD_SHIPPING, 'multiselect',
		    [
		        'name' 		=> static::FIELD_SHIPPING,
		        'label' 	=> __('Allowed shipping methods'),
		        'title' 	=> __('Allowed shipping methods'),
		        'required' 	=> false,
		        'values' 	=> $this->getShippingMethods(),
		        'value'     => $value_shipping
		    ]);
		
		return $form;
	} 
	
	public function afterExecute(\Magento\Customer\Controller\Adminhtml\Group\Save $save, $result)
	{
		/**
		 * Get code
		 * 
		 * @var string $code
		 */
		$code = $save->getRequest()->getParam('code');
		
		if(empty($code)) 
		{
			$code = 'NOT LOGGED IN';
		}
		
		/**
		 * Create search filter 
		 * 
		 * @var array $_filter
		 */
		$_filter = 
		[ 
			$this->_filterBuilder->setField('customer_group_code')->setConditionType('eq')->setValue($code)->create()
		];
		
		/**
		 * Load all groups by code 
		 * 
		 * @var array $customerGroups
		 */
		$customerGroups = $this->_groupRepository->getList($this->_searchCriteriaBuilder->addFilters($_filter)->create())->getItems();
		
		/**
		 * Get first group 
		 * 
		 * @var Ambiguous $customerGroup
		 */
		$customerGroup = array_shift($customerGroups);
		
		if($customerGroup)
		{
		    /**
		     * Delete current payment filters for that group
		     */
		    $collection = $this->groupPaymentCollectionFactory->create()->addFieldToFilter('entity_group_id', $customerGroup->getId());
		    
		    if ($collection->getSize())
		    {
		        $collection->walk('delete');
		    }
		    
		    /**
		     * Delete current shipping filters for that group
		     */
		    $collection_shipping = $this->groupShippingCollectionFactory->create()->addFieldToFilter('entity_group_id', $customerGroup->getId());
		    
		    if ($collection_shipping->getSize())
		    {
		        $collection_shipping->walk('delete');
		    }
		    
		    $methods = $save->getRequest()->getParam('group_payment');
		    
		    if ($methods)
		    {
		        foreach ($methods as $method)
		        {
		            try 
		            {
    		            $filter = $this->groupPaymentFactory->create();
    		            
    		            $filter->setEntityGroupId($customerGroup->getId());
    		            $filter->setEntityGroupPayment($method);
    		            
    		            $filter->save();
		            }
		            catch (\Exception $e)
		            {
		                $this->messageManager->addErrorNotice($e->getMessage());
		            }
		        }
		    }
		    
		    $methods_shipping = $save->getRequest()->getParam('group_shipping');
		    
		    if ($methods_shipping)
		    {
		        foreach ($methods_shipping as $method)
		        {
		            try
		            {
		                $filter = $this->groupShippingFactory->create();
		                
		                $filter->setEntityGroupId($customerGroup->getId());
		                $filter->setEntityGroupShipping($method);
		                
		                $filter->save();
		            }
		            catch (\Exception $e)
		            {
		                $this->messageManager->addErrorNotice($e->getMessage());
		            }
		        }
		    }
		}
		return $result;
	} 
	
	/**
	 * Get shipping methods 
	 * 
	 * @return array
	 */
	private function getShippingMethods() : array
	{
	    $activeCarriers = $this->shippingConfig->getActiveCarriers();
	    
	    foreach($activeCarriers as $carrierCode => $carrierModel) 
	    {
	        $options = array();
	        
	        if ($carrierMethods = $carrierModel->getAllowedMethods())
	        {
	            foreach ($carrierMethods as $methodCode => $method) 
	            {
	                $code = join(static::SEPARATOR, [$carrierCode, $methodCode]); 
 
	                $options[] = ['value' => $code, 'label' => $method];
	            }
	            
	            $carrierTitle = $this->scopeConfig->getValue("carriers/{$carrierCode}/title");
	        }
	        
	        $methods[] = ['value' => $options, 'label' => $carrierTitle];
	    }

	    return $methods;    
	}
}
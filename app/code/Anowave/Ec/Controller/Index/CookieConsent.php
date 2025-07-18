<?php
/**
 * Anowave Magento 2 Google Tag Manager Enhanced Ecommerce (UA) Tracking
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * https://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2024 Anowave (https://www.anowave.com/)
 * @license  	https://www.anowave.com/license-agreement/
 */
 
namespace Anowave\Ec\Controller\Index;

class CookieConsent extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $resultJsonFactory;
	
	/**
	 * @var \Anowave\Ec\Model\ConsentCookie
	 */
	protected $consentCookieFactory;
	
	/**
	 * @var \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory
	 */
	protected $consentCookieCollectionFactory;

	/**
	 * Constructor 
	 * 
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 * @param \Anowave\Ec\Model\ConsentCookieFactory $consentCookieFactory
	 * @param \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory $consentCookieCollectionFactory
	 */
	public function __construct
	(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Anowave\Ec\Model\ConsentCookieFactory $consentCookieFactory,
	    \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory $consentCookieCollectionFactory
	)
	{
		parent::__construct($context);
		
		/**
		 * Set response type factory 
		 * 
		 * @var \Magento\Framework\Controller\Result\JsonFactory
		 */
		$this->resultJsonFactory = $resultJsonFactory;

		/**
		 * Set cookie directive 
		 * 
		 * @var \Anowave\Ec\Controller\Index\Cookie $directive
		 */
		$this->consentCookieFactory = $consentCookieFactory;
		
		/**
		 * Set consent cookie collection factory 
		 * 
		 * @var \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory $consentCookieCollectionFactory
		 */
		$this->consentCookieCollectionFactory = $consentCookieCollectionFactory;
	}

	/**
	 * Execute controller
	 *
	 * @see \Magento\Framework\App\ActionInterface::execute()
	 */
	public function execute()
	{ 
	    /**
	     * Get cookies
	     * 
	     * @var array $cookies
	     */
	    $cookies = (array) $this->getRequest()->getParam('cookies');
	    
	    /**
	     * Default errors
	     * 
	     * @var array $errors
	     */
	    $errors = [];
	    
	    if ($cookies)
	    {
	        
	        $map = [];
	        
	        foreach($this->consentCookieCollectionFactory->create()->addFieldToFilter('cookie_name', ['in' => array_keys($cookies)]) as $entity)
	        {
	            $map[] = $entity->getCookieName();
	        }
	        
	        $cookies = array_filter($cookies,function ($key) use ($map) 
            {
                return !in_array($key, $map);
            },
            ARRAY_FILTER_USE_KEY);
	        
	        
	        
	        foreach ($cookies as $name => $value)
	        {
	            try 
	            {
    	            $cookie = $this->consentCookieFactory->create();
    	            
    	            $cookie->setCookieName($name);
    	            $cookie->save();
	            }
	            catch (\Exception $e)
	            {
	                $errors[$name] = false;
	            }
	        }
	    }
	    
		return $this->resultJsonFactory->create()->setData($errors);
	}
}
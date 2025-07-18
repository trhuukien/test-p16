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

namespace Anowave\Ec\Controller\Adminhtml\ConsentCookie;

use Anowave\Ec\Model\ConsentCookie as ConsentCookie;

class Update extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;
    
    /**
     * @var \Anowave\Ec\Model\ConsentCookieFactory
     */
    protected $consentCookieFactory;
    
    /**
     * Constructor 
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Anowave\Ec\Model\ConsentCookieFactory $consentCookieFactory
     */
    public function __construct
    (
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Anowave\Ec\Model\ConsentCookieFactory $consentCookieFactory
    ) 
    {
        parent::__construct($context);
        
        /**
         * Set JSON factory 
         * 
         * @var \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
         */
        $this->jsonFactory = $jsonFactory;
        
        /**
         * Set consent cookie factory 
         * 
         * @var \Anowave\Ec\Model\ConsentCookieFactory $consentCookieFactory
         */
        $this->consentCookieFactory = $consentCookieFactory;
    }
    
	public function execute()
	{
	    $resultJson = $this->jsonFactory->create();
	    
	    $messages = [];
	    
	    if ($this->getRequest()->getParam('isAjax')) 
	    {
	        $postItems = $this->getRequest()->getParam('items', []);
	        
	        if (!count($postItems)) 
	        {
	            $messages[] = __('Please correct the data sent.');
	        } 
	        else 
	        {
	            foreach (array_keys($postItems) as $entityId) 
	            {
	                /** load your model to update the data */
	                $model = $this->consentCookieFactory->create()->load($entityId);
	                
	                try 
	                {
	                    $model->setData(array_merge($model->getData(), $postItems[$entityId]));
	                    $model->save();
	                } 
	                catch (\Exception $e) 
	                {
	                    $messages[] = "[Error:]  {$e->getMessage()}";
	                }
	            }
	        }
	    }
	    
	    return $resultJson->setData(
        [
	        'messages' => $messages,
	        'error'    => 0 < count($messages)
	    ]);
	}
}
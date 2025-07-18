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

namespace Anowave\Ec\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class Newsletter implements ObserverInterface
{
	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $session;
	
	/**
	 * @var \Anowave\Ec\Helper\Json
	 */
	protected $jsonHelper;
	
	/**
	 * @var \Magento\Framework\App\RequestInterface
	 */
	protected $request;
	
	/**
	 * Constructor 
	 * 
	 * @param \Magento\Customer\Model\SessionFactory $sessionFactory
	 * @param \Anowave\Ec\Helper\Json $jsonHelper
	 * @param \Magento\Framework\App\RequestInterface $request
	 */
	public function __construct
	(
		\Magento\Customer\Model\SessionFactory $sessionFactory,
		\Anowave\Ec\Helper\Json $jsonHelper,
	    \Magento\Framework\App\RequestInterface $request
	)
	{
		/**
		 * Set session
		 *
		 * @var \Magento\Customer\Model\Session $session
		 */
		$this->session = $sessionFactory->create();
		
		/**
		 * Set JSON helper
		 *
		 * @var \Anowave\Ec\Helper\Json $jsonHelper
		 */
		$this->jsonHelper = $jsonHelper;
		
		/**
		 * Set request 
		 * 
		 * @var \Magento\Framework\App\RequestInterface $request
		 */
		$this->request = $request;
	}
	
	/**
	 * Execute (non-PHPdoc)
	 * 
	 * @see \Magento\Framework\Event\ObserverInterface::execute()
	 */
	public function execute(EventObserver $observer)
	{
	    /**
	     * Check for subscription 
	     * 
	     * @var int $subscribed
	     */
	    $subscribed = $this->request->getParam('is_subscribed');
	    
	    if (is_null($subscribed))
	    {
	        $subscribed = true;
	    }
	    else 
	    {
	        $subscribed = 1 === (int) $subscribed;
	    }   
	    
	    $email = $this->request->getParam('email');
	    
	    if ($subscribed)
	    {
	        /**
	         * Get email
	         * 
	         * @var string $email
	         */
	        $this->session->setNewsletterEvent($this->jsonHelper->encode(
            [
                'event' 			=>    'newsletterSubmit',
                'eventCategory' 	=> __('Newsletter'),
                'eventAction' 		=> __('Submit'),
                'eventLabel' 		=> __('Subscribe'),
                'eventEmail'        => filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
                'eventValue' 		=> 1
            ]));
	    }
	    else 
	    {
	        $this->session->setNewsletterEvent($this->jsonHelper->encode(
            [
                'event' 			=>    'newsletterUnsibscribeSubmit',
                'eventCategory' 	=> __('Newsletter'),
                'eventAction' 		=> __('Submit'),
                'eventLabel' 		=> __('Unsubscribe'),
                'eventEmail'        => filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
                'eventValue' 		=> 1
            ]));
	    }

	}
}
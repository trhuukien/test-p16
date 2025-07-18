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

class Cookie extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var \Magento\Framework\Controller\Result\JsonFactory
	 */
	protected $resultJsonFactory;
	
	/**
	 * @var \Anowave\Ec\Helper\Data
	 */
	protected $helper;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\Directive
	 */
	protected $directive;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectiveMarketing
	 */
	protected $directiveMarketing;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectivePreferences
	 */
	protected $directivePreferences;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectiveAnalytics
	 */
	protected $directiveAnalytics;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectiveUserdata
	 */
	protected $directiveUserdata;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectivePersonalization
	 */
	protected $directivePersonalization;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectiveUuid
	 */
	protected $directiveUuid;
	
	/**
	 * @var \Anowave\Ec\Model\Cookie\DirectiveDecline
	 */
	protected $directiveDecline;
	
	/**
	 * @var \Anowave\Ec\Model\ConsentFactory
	 */
	protected $consentFactory;
	
	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Constructor
	 * 
	 * @param \Magento\Framework\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 * @param \Anowave\Ec\Helper\Data $helper
	 * @param \Anowave\Ec\Model\Cookie\Directive $directive
	 * @param \Anowave\Ec\Model\Cookie\DirectiveMarketing $directiveMarketing
	 * @param \Anowave\Ec\Model\Cookie\DirectivePreferences $directivePreferences
	 * @param \Anowave\Ec\Model\Cookie\DirectiveAnalytics $directiveAnalytics
	 * @param \Anowave\Ec\Model\Cookie\DirectiveUserdata $directiveUserdata
	 * @param \Anowave\Ec\Model\Cookie\DirectivePersonalization $directivePersonalization
	 * @param \Anowave\Ec\Model\Cookie\DirectiveDecline $directiveDecline
	 * @param \Anowave\Ec\Model\Cookie\DirectiveUuid $directiveUuid
	 * @param \Anowave\Ec\Model\ConsentFactory $consentFactory
	 * @param \Psr\Log\LoggerInterface $logger
	 */
	public function __construct
	(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Anowave\Ec\Helper\Data $helper,
		\Anowave\Ec\Model\Cookie\Directive $directive,
		\Anowave\Ec\Model\Cookie\DirectiveMarketing $directiveMarketing, 
		\Anowave\Ec\Model\Cookie\DirectivePreferences $directivePreferences,
		\Anowave\Ec\Model\Cookie\DirectiveAnalytics $directiveAnalytics,
	    \Anowave\Ec\Model\Cookie\DirectiveUserdata $directiveUserdata,
	    \Anowave\Ec\Model\Cookie\DirectivePersonalization $directivePersonalization,
		\Anowave\Ec\Model\Cookie\DirectiveDecline $directiveDecline,
	    \Anowave\Ec\Model\Cookie\DirectiveUuid $directiveUuid,
	    \Anowave\Ec\Model\ConsentFactory $consentFactory,
	    \Psr\Log\LoggerInterface $logger
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
		 * Set helper 
		 * 
		 * @var \Anowave\Ec\Helper\Data
		 */
		$this->helper = $helper;
		
		/**
		 * Set cookie directive 
		 * 
		 * @var \Anowave\Ec\Model\Cookie\Directive $directive
		 */
		$this->directive = $directive;
		
		/**
		 * Set cookie directive
		 *
		 * @var \Anowave\Ec\Model\Cookie\DirectiveMarketing $directiveMarketing
		 */
		$this->directiveMarketing = $directiveMarketing;
		
		/**
		 * Set cookie directive
		 *
		 * @var \Anowave\Ec\Model\Cookie\DirectivePreferences $directivePreferences
		 */
		$this->directivePreferences = $directivePreferences;
		
		/**
		 * Set cookie directive
		 *
		 * @var \Anowave\Ec\Model\Cookie\DirectiveAnalytics $directiveAnalytics
		 */
		$this->directiveAnalytics = $directiveAnalytics;
		
		/**
		 * Set ad user data cookie
		 * 
		 * @var \Anowave\Ec\Model\Cookie\DirectiveUserdata $directiveUserdata
		 */
		$this->directiveUserdata = $directiveUserdata;
		
		/**
		 * Set personalization
		 *
		 * @var \Anowave\Ec\Model\Cookie\DirectivePersonalization $directivePersonaliztion
		 */
		
		$this->directivePersonalization = $directivePersonalization;
		
		/**
		 * Set decline cookie 
		 * 
		 * @var \Anowave\Ec\Model\Cookie\DirectiveDecline $directiveDecline
		 */
		$this->directiveDecline = $directiveDecline;
		
		/**
		 * Set UUID directive cookie 
		 * 
		 * @var \Anowave\Ec\Model\Cookie\DirectiveUuid $directiveUuid
		 */
		$this->directiveUuid = $directiveUuid;
		
		
		/**
		 * Set consent factory 
		 * 
		 * @var \Anowave\Ec\Model\ConsentFactory $consentFactory
		 */
		$this->consentFactory = $consentFactory;
		
		/**
		 * Set logger 
		 * 
		 * @var \Psr\Log\LoggerInterface $logger
		 */
		$this->logger = $logger;
	}

	/**
	 * Execute controller
	 *
	 * @see \Magento\Framework\App\ActionInterface::execute()
	 */
	public function execute()
	{ 
		$lifetime = (3600 * 24 * 30);
		
		/**
		 * Check if cookies are declined
		 */
		if ($this->getRequest()->getParam('decline'))
		{
			$this->directiveDecline->set(1, $lifetime);
			
		    $grant = 
		    [
			    \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_DECLINE_EVENT => true
			];
		    
		    if ($this->helper->getCookieDirectiveIsSegmentMode())
		    {
		        foreach ($this->helper->getCookieDirectiveConsentSegments() as $segment)
		        {
		            switch($segment)
		            {
		                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_MARKETING_GRANTED_EVENT: 		$this->directiveMarketing->set(0, $lifetime); 	    break;
		                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT:	$this->directivePreferences->set(0, $lifetime); 	    break;
		                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT:		$this->directiveAnalytics->set(0, $lifetime);	    break;
		                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_USER_DATA_EVENT:		    $this->directiveUserdata->set(0, $lifetime); 	    break;
		                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_PERSONALIZATION_EVENT:		$this->directivePersonalization->set(0, $lifetime); break;
		            }
		        }
		    }
		    
		    $this->directive->set(0, $lifetime);
		    
		    $uuid = $this->keep($grant);
		    
		    if ($uuid)
		    {
		        $this->directiveUuid->set($uuid);
		    }
			
			return $this->resultJsonFactory->create()->setData($grant);
		}
		else 
		{
		    $this->directiveDecline->delete();
		}

		/**
		* Set cookie consent
		*/
		$this->directive->set(1, $lifetime);
		
		$grant = 
		[
			\Anowave\Ec\Helper\Constants::COOKIE_CONSENT_GRANTED_EVENT => true
		];

		if ($this->helper->getCookieDirectiveIsSegmentMode())
		{
			/**
			 * Get segments
			 * 
			 * @var [] $segments
			 */
			if ($this->getRequest()->getParam('cookie'))
			{
				$segments = (array) $this->getRequest()->getParam('cookie');
			}
			else 
			{
				$segments = [];
			}
			
			/**
			 * Delete previously granted segments
			 */
			foreach ($this->helper->getCookieDirectiveConsentSegments() as $segment)
			{
				if (!in_array($segment, $segments))
				{
					switch($segment)
					{
						case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_MARKETING_GRANTED_EVENT: 		$this->directiveMarketing->set(0, $lifetime); 	    break;
						case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT:	$this->directivePreferences->set(0, $lifetime);     break;
						case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT:		$this->directiveAnalytics->set(0, $lifetime);	    break;
						case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_USER_DATA_EVENT:		    $this->directiveUserdata->set(0, $lifetime); 	    break;
						case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_PERSONALIZATION_EVENT:		$this->directivePersonalization->set(0, $lifetime); break;
					}
				}
			}
			
			foreach ($segments as $segment)	
			{
				switch($segment)
				{
					case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_MARKETING_GRANTED_EVENT: 		$this->directiveMarketing->set(1, $lifetime); 	    break;
					case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT:	$this->directivePreferences->set(1, $lifetime);     break;
					case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT:		$this->directiveAnalytics->set(1, $lifetime); 	    break;
					case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_USER_DATA_EVENT:		    $this->directiveUserdata->set(1, $lifetime); 	    break;
					case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_PERSONALIZATION_EVENT:		$this->directivePersonalization->set(1, $lifetime); break;
				}
				
				$grant[$segment] = true;
			}
		}
		
		$uuid = $this->keep($grant);
		
		if ($uuid)
		{
		    $this->directiveUuid->set($uuid);
		}
		
		return $this->resultJsonFactory->create()->setData($grant);
	}
	
	/**
	 * Store consent locally 
	 * 
	 * @param array $consent
	 * @return string
	 */
	public function keep(array $consent = []) : string
	{
	    $uuid = $this->uuid();
	    
	    try
	    {
	        $entity = $this->consentFactory->create();
	        
	        $entity->setConsentUuid($uuid);
	        $entity->setConsentIp(ip2long($_SERVER['REMOTE_ADDR']));
	        $entity->setConsent(json_encode($consent));
	        $entity->save();
	    }
	    catch (\Exception $e)
	    {
	        $this->logger->error($e->getMessage());
	    }
	    
	    return $uuid;
	}
	
	/**
	 * Generate random UUID 
	 * 
	 * @return string
	 */
	protected function uuid() : string
	{
	    if (function_exists('com_create_guid') === true)
	    {
	        return trim(com_create_guid(), '{}');
	    }
	    
	    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
}
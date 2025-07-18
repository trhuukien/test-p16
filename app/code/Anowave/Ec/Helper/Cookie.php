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

namespace Anowave\Ec\Helper;

use Anowave\Package\Helper\Package;

class Cookie extends \Anowave\Package\Helper\Package
{
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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @var \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory
     */
    protected $consentCookieCollectionFactory;
    
    /**
     * Check flag 
     * 
     * @var string
     */
    private $check = false;
    
    /**
     * Constructor
     * 
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Anowave\Ec\Model\Cookie\Directive $directive
     * @param \Anowave\Ec\Model\Cookie\DirectiveMarketing $directiveMarketing
     * @param \Anowave\Ec\Model\Cookie\DirectivePreferences $directivePreferences
     * @param \Anowave\Ec\Model\Cookie\DirectiveAnalytics $directiveAnalytics
     * @param \Anowave\Ec\Model\Cookie\DirectiveUserdata $directiveUserdata
     * @param \Anowave\Ec\Model\Cookie\DirectivePersonalization $directivePersonalization
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory $consentCookieCollectionFactory
     */
    public function __construct
    (
        \Magento\Framework\App\Helper\Context $context,
        \Anowave\Ec\Model\Cookie\Directive $directive,
        \Anowave\Ec\Model\Cookie\DirectiveMarketing $directiveMarketing,
        \Anowave\Ec\Model\Cookie\DirectivePreferences $directivePreferences,
        \Anowave\Ec\Model\Cookie\DirectiveAnalytics $directiveAnalytics,
        \Anowave\Ec\Model\Cookie\DirectiveUserdata $directiveUserdata,
        \Anowave\Ec\Model\Cookie\DirectivePersonalization $directivePersonalization,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory $consentCookieCollectionFactory
    )
    {
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
        * Set ad_user_data cookie directive
        *
        * @var \Anowave\Ec\Model\Cookie\DirectiveUserdata $directiveUserdata
        */
        $this->directiveUserdata = $directiveUserdata;
        
        /**
         * Set ad_personalization cookie directive
         *
         * @var \Anowave\Ec\Model\Cookie\DirectivePersonalization $directivePersonalization
         */
        
        $this->directivePersonalization = $directivePersonalization;
        
        /**
         * Set scope config 
         * 
         * @var \Magento\Framework\App\Config\ScopeConfigInterface
         */
        $this->scopeConfig = $scopeConfig;
        
        /**
         * Set consent cookie collection factory 
         * 
         * @var \Anowave\Ec\Model\ResourceModel\ConsentCookie\CollectionFactory $consentCookieCollectionFactory
         */
        $this->consentCookieCollectionFactory = $consentCookieCollectionFactory;
        
        /**
         * Set check flag 
         * 
         */
        $this->check = (int) $scopeConfig->getValue('ec/cookie/mode_segment_checkall');
    }
    
    /**
     * Get segments 
     * 
     * @return array
     */
    public function getSegments() : array
    {
        /**
         * Cookie consent scopes
         *
         * @var array $scope
         */
        $scope =
        [
            \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_GRANTED_EVENT 				=> (int) $this->directive->get(),
            \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_MARKETING_GRANTED_EVENT 	=> (int) $this->directiveMarketing->get(),
            \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT 	=> (int) $this->directivePreferences->get(),
            \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT 	=> (int) $this->directiveAnalytics->get(),
            \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_USER_DATA_EVENT 	    => (int) $this->directiveUserdata->get(),
            \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_PERSONALIZATION_EVENT 	=> (int) $this->directivePersonalization->get(),
        ];
        
        /**
         * Get current granted consent scope
         *
         * @var array $grant
         */
        $grant = array_filter($scope);
        
        /**
         * Cookie segments 
         * 
         * @var array $segments
         */
        $segments = [];
        
        /**
         * Get acquired cookies classification 
         * 
         * @var array $classification
         */
        $classification = $this->getSegmentsCookies();
        
        
        foreach ($scope as $key => $value)
        {
            $cookies = (array_key_exists($key, $classification)) ? $classification[$key] : [];
                 
            switch ($key)
            {
                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_MARKETING_GRANTED_EVENT:
                    
                    $segments[\Anowave\Ec\Helper\Constants::COOKIE_CONSENT_MARKETING_GRANTED_EVENT] =
                    [
                        'label' => __('Marketing cookies'),
                        'value' => __('Marketing cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.'),
                        'check' => $this->getDefaultValue
                        (
                            $this->directiveMarketing->get()
                        ),
                        'cookies' => $cookies
                    ];
                    
                    break;
                    
                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT:
                    
                    $segments[\Anowave\Ec\Helper\Constants::COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT] =
                    [
                        'label' => __('Preferences cookies'),
                        'value' => __('Preference cookies enable a website to remember information that changes the way the website behaves or looks, like your preferred language or the region that you are in.'),
                        'check' => $this->getDefaultValue
                        (
                            $this->directivePreferences->get()
                        ),
                        'cookies' => $cookies
                    ];
                    
                    break;
                    
                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT:
                    
                    $segments[\Anowave\Ec\Helper\Constants::COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT] =
                    [
                        'label' => __('Analytics cookies'),
                        'value' => __('Statistic cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.'),
                        'check' => $this->getDefaultValue
                        (
                            $this->directiveAnalytics->get()
                        ),
                        'cookies' => $cookies
                    ];
                    
                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_USER_DATA_EVENT:
                    
                    $segments[\Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_USER_DATA_EVENT] =
                    [
                        'label' => __('Allow sending user data to Google for advertising purposes'),
                        'value' => __('Sets consent for sending user data related to advertising to Google.'),
                        'check' => $this->getDefaultValue
                        (
                            $this->directiveUserdata->get()
                        ),
                        'cookies' => $cookies
                    ];
                    
                case \Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_PERSONALIZATION_EVENT:
                    
                    $segments[\Anowave\Ec\Helper\Constants::COOKIE_CONSENT_AD_PERSONALIZATION_EVENT] =
                    [
                        'label' => __('Allow personalized advertising (remarketing)'),
                        'value' => __('Sets consent for personalized advertising.'),
                        'check' => $this->getDefaultValue
                        (
                            $this->directivePersonalization->get()
                        ),
                        'cookies' => $cookies
                    ];
                    
                    break;
            }
        }

        return $segments;
    }
    
    /**
     * Get acquired cookies grouped by segment
     * 
     * @return array
     */
    public function getSegmentsCookies() : array
    {
        $cookies = [];
        
        foreach ($this->consentCookieCollectionFactory->create() as $entity)
        {
            $segment = $entity->getCookieSegment();
            
            if (!array_key_exists($segment, $cookies))
            {
                $cookies[$segment] = [];
            }
            
            $cookies[$segment][$entity->getCookieName()] = $entity->getCookieDescription();
        }
        
        return $cookies;
    }
    
    /**
     * Get default cookie choice
     * 
     * @param unknown $value
     * @return bool
     */
    private function getDefaultValue($value) : bool
    {
        if (is_null($value))
        {
            if ($this->check)
            {
                return true;
            }
        }
        else 
        {
            return 1 === (int) $value;
        }
        
        return false;
    }
}
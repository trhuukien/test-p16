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

namespace Anowave\Ec\Controller\Adminhtml\Analytics;

use Magento\Backend\App\Action;

class Statistics extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    
    /**
     * Transaction collection factory
     * @var unknown
     */
    protected $transactionCollectionFactory;
    
    /**
     * @var \Magento\Framework\App\Resource
     */
    protected $resource;
    
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Anowave\Ec\Model\ResourceModel\Transaction\CollectionFactory $transactionCollectionFactory
     * @param \Magento\Framework\App\Resource $resource
     */
    public function __construct
    (
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Anowave\Ec\Model\ResourceModel\Transaction\CollectionFactory $transactionCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource
    )
    {
        /**
         * Set result JSON factory
         *
         * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
         */
        $this->resultJsonFactory = $resultJsonFactory;
        
        /**
         * Set transaction collection factory
         * @var \Anowave\Ec\Model\ResourceModel\Transaction\CollectionFactory $transactionCollectionFactory
         */
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        
        /**
         * Set resource
         *
         * @var \Magento\Framework\App\Resource $consentCollectionFactory
         */
        $this->resource = $resource;
        
        parent::__construct($context);
    }
    
    /**
     * Hello test controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        
        $data =
        [
            'placed'                        => $this->getPlaced(),
            'placed_admin'                  => $this->getPlacedAdmin(),
            'tracked'                       => $this->getTracked(),
            'tracked_admin'                 => $this->getTrackedAdmin(),
            'consent_grant'                 => $this->getConsentGrant(),
            'consent_decline'               => $this->getConsentDecline(),
            'consent_grant_marketing'       => $this->getConsentMarketingGrant(),
            'consent_grant_preferences'     => $this->getConsentPreferencesGrant(),
            'consent_grant_analytics'       => $this->getConsentAnalyticsGrant(),
            'consent_grant_userdata'        => $this->getConsentUserdataGrant(),
            'consent_grant_personalization' => $this->getConsentPersonalizationGrant(),
            'total'                         => $this->getTotal()
        ];
        
        $result->setData($data);
        
        return $result;
    }
    
    /**
     * Get number of placed but not tracked orders
     *
     * @return int
     */
    public function getPlaced() : int
    {
        return $this->transactionCollectionFactory->create()->addFieldToFilter('ec_track',  \Anowave\Ec\Helper\Constants::FLAG_PLACED)->addFieldToFilter('ec_order_type',\Anowave\Ec\Helper\Constants::ORDER_TYPE_FRONTEND)->getSize();
    }
    
    /**
     * Get number of placed but not tracked admin orders
     *
     * @return int
     */
    public function getPlacedAdmin() : int
    {
        return $this->transactionCollectionFactory->create()->addFieldToFilter('ec_track',  \Anowave\Ec\Helper\Constants::FLAG_PLACED)->addFieldToFilter('ec_order_type',\Anowave\Ec\Helper\Constants::ORDER_TYPE_BACKEND)->getSize();
    }
    
    /**
     * Get number of placed and tracked orders
     *
     * @return int
     */
    public function getTracked() : int
    {
        return $this->transactionCollectionFactory->create()->addFieldToFilter('ec_track', \Anowave\Ec\Helper\Constants::FLAG_TRACKED)->addFieldToFilter('ec_order_type',\Anowave\Ec\Helper\Constants::ORDER_TYPE_FRONTEND)->getSize();
    }
    
    /**
     * Get number of placed and tracked admin orders
     *
     * @return int
     */
    public function getTrackedAdmin() : int
    {
        return $this->transactionCollectionFactory->create()->addFieldToFilter('ec_track', \Anowave\Ec\Helper\Constants::FLAG_TRACKED)->addFieldToFilter('ec_order_type',\Anowave\Ec\Helper\Constants::ORDER_TYPE_BACKEND)->getSize();
    }
    
    /**
     * Get total number of transactions placed
     *
     * @return int
     */
    public function getTotal() : int
    {
        return $this->transactionCollectionFactory->create()->getSize();
    }
    
    public function getConsentGrant() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentGranted") IS NOT NULL');
        
    }
    
    public function getConsentDecline() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentDeclined") IS NOT NULL');
    }
    
    public function getConsentMarketingGrant() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentMarketingGranted") IS NOT NULL');
    }
    
    public function getConsentPreferencesGrant() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentPreferencesGranted") IS NOT NULL');
    }
    
    public function getConsentAnalyticsGrant() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentAnalyticsGranted") IS NOT NULL');
    }
    
    public function getConsentUserdataGrant() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentUserdata") IS NOT NULL');
    }
    
    public function getConsentPersonalizationGrant() : int
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        
        return (int) $connection->fetchOne('SELECT COUNT(consent_id) FROM ' . $this->resource->getTableName('ae_ec_gdpr') . ' WHERE JSON_EXTRACT(consent, "$.cookieConsentPersonalization") IS NOT NULL');
    }
    
    protected function _isAllowed()
    {
        return true;
    }
}
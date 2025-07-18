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

namespace Anowave\FilterPayment\Model\Config\Source;

class Methods extends \Magento\Payment\Model\Config\Source\Allmethods
{
    /**
     * @var \Magento\Payment\Model\Config
     */
    protected $paymentConfig;
    
    /**
     *
     * @var unknown
     */
    protected $scope;
    
    /**
     * Constructor
     *
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Payment\Model\Config $paymentConfig
     */
    public function __construct
    (
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Payment\Model\Config $paymentConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scope
        )
    {
        $this->paymentConfig = $paymentConfig;
        
        parent::__construct($paymentData);
    }
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        /**
         * Get active methods
         *
         * @var array $active
         */
        $active = array_keys($this->paymentConfig->getActiveMethods());
        
        /**
         * Filter
         *
         * @var \Closure $filter
         */
        $filter = function($method) use ($active, &$filter)
        {
            if (is_string($method))
            {
                if (!in_array($method, $active))
                {
                    return false;
                }
            }
            elseif (is_array($method))
            {
                $method = array_filter($method, $filter);
                
                if (!$method)
                {
                    return false;
                }
            }
            
            return true;
        };
        
        $methods = array_filter($this->_paymentData->getPaymentMethodList(true, true, true), $filter);
        
        foreach ($methods as &$method)
        {
            if (is_array($method) && isset($method['value']))
            {
                if (is_array($method['value']))
                {
                    $method['value'] = array_filter($method['value'], $filter);
                }
            }
        }
        
        unset($method);
        
        return $methods;
    }
}
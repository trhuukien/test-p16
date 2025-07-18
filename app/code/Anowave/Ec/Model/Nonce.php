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
 
namespace Anowave\Ec\Model;

class Nonce
{
    /**
     * @var \Magento\Csp\Helper\CspNonceProvider
     */
    protected $nonceProvider;
    
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    
    /**
     * Constructor 
     * 
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct
    (
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        if (class_exists('\Magento\Csp\Helper\CspNonceProvider'))
        {
            $this->nonceProvider = $objectManager->create('\Magento\Csp\Helper\CspNonceProvider');
        }
    }
    
    /**
     * Generate nonce 
     * 
     * @return string
     */
    public function generateNonce() : string
    {
        if (!$this->nonceProvider)
        {
            return base64_encode(hash('sha256', uniqid(), true));
        }
            
        return $this->nonceProvider->generateNonce(); 
    }
}
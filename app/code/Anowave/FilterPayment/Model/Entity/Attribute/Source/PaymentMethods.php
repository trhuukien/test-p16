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

class PaymentMethods extends AbstractSource
{
    /**
     * @var Data
     */
    protected $paymentData;

    /**
     * Constructor 
     * 
     * @param Data $paymentData
     */
    public function __construct(Data $paymentData)
    {
        $this->paymentData = $paymentData;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $errors = [];
        
        try 
        {
            if ($this->_options === null) 
            {
                $this->_options = [];
                
                $paymentMethods = $this->paymentData->getPaymentMethodList();
                
                foreach ($paymentMethods as $code => $paymentMethod) 
                { 
                    if (isset($paymentMethod['title'])) 
                    {
                        $label = $paymentMethod['title'];
                    }
                    else
                    {
                        try 
                        {
                            $label = $this->paymentData->getMethodInstance($code)->getConfigData('title', null);
                        }
                        catch (\Exception $e)
                        {
                            $label = $code;
                        }
                    }   
    
                    if ($label) 
                    {
                        $this->_options[$code] = ['label' => $label,'value' => $code];
                    }
                   
                }
    
                usort($this->_options, function($a, $b) 
                {
                    return strcmp($a['value'], $b['value']);
                });
            }
        }
        catch (\Exception $e)
        {
            $errors[] = $e->getMessage();
        }
        
        return $this->_options;
    }
}
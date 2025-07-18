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


namespace Anowave\FilterPayment\Helper;

use Anowave\Package\Helper\Package;

class Data extends \Anowave\Package\Helper\Package
{
	/**
	 * Package name
	 * @var string
	 */
	protected $package = 'MAGE2-FILTERPAYMENT';
	
	/**
	 * Config path 
	 * @var string
	 */
	protected $config = 'filterpayment/general/license';
	
	/**
	 * Check if filter payment is active 
	 * 
	 * @return mixed
	 */
	public function isActive()
	{
		return $this->getConfig('filterpayment/general/active');
	}
}
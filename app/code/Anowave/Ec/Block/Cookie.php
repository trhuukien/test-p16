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

namespace Anowave\Ec\Block;

class Cookie extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var \Anowave\Ec\Helper\Data
	 */
	protected $helper;
	
	/**
	 * @var \Anowave\Ec\Helper\Cookie
	 */
	protected $helperCookie;
	
	/**
	 * Constructor 
	 * 
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Anowave\Ec\Helper\Data $helper
	 * @param \Anowave\Ec\Helper\Cookie $helperCookie
	 * @param array $data
	 */
	public function __construct
	(
		\Magento\Framework\View\Element\Template\Context $context,
		\Anowave\Ec\Helper\Data $helper,
	    \Anowave\Ec\Helper\Cookie $helperCookie,
		array $data = []
	) 
	{
		/**
		 * Set Helper
		 * @var \Anowave\Ec\Helper\Data 
		 */
		$this->helper = $helper;
		
		/**
		 * Set cookie helper 
		 * 
		 * @var \Anowave\Ec\Helper\Cookie
		 */
		$this->helperCookie = $helperCookie;
		
		/**
		 * Parent constructor
		 */
		parent::__construct($context, $data);
	}
	
	public function getHelper()
	{
	    return $this->helper;
	}
	
	public function getCheckAll() : bool
	{
	    return 1 === (int) $this->helper->getSegmentCheckall();
	}
	
	/**
	 * Get cookie segments 
	 * 
	 * @return array
	 */
	public function getSegments() : array
	{
	    return $this->helperCookie->getSegments();
	}
	
	/**
	 * Get segment mode 
	 * 
	 * @return bool
	 */
	public function getSegmentMode() : bool
	{
	    return $this->helper->getCookieDirectiveIsSegmentMode();
	}
	
	/**
	 * Render template
	 * 
	 * {@inheritDoc}
	 * @see \Magento\Framework\View\Element\Template::_toHtml()
	 */
	protected function _toHtml()
	{
	    return !$this->helper->isActive() ? '' : $this->helper->filter(parent::_toHtml());
	}
}
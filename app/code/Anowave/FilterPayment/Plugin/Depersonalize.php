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
 * @package 	Anowave_FilterPayment
 * @copyright 	Copyright (c) 2021 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */

namespace Anowave\FilterPayment\Plugin;

use Magento\PageCache\Model\DepersonalizeChecker;
use Magento\Framework\Registry;

/**
 * Google Analytics module observer
 *
 */
class Depersonalize
{
	/**
	 * @var DepersonalizeChecker
	 */
	protected $depersonalizeChecker;
	
	/**
	 * @var \Magento\Framework\Session\SessionManagerInterface
	 */
	protected $session;
	
	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $customerSession;
	
	/**
	 * @var \Magento\Customer\Model\CustomerFactory
	 */
	protected $customerFactory;
	
	/**
	 * @var \Magento\Customer\Model\Visitor
	 */
	protected $visitor;
	
	/**
	 * @var int
	 */
	protected $customerGroupId;
	
	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;
	
	/**
	 * @var string
	 */
	protected $formKey;
	
	/**
	 * @param DepersonalizeChecker $depersonalizeChecker
	 * @param \Magento\Framework\Session\SessionManagerInterface $session
	 * @param \Magento\Customer\Model\Session $customerSession
	 * @param \Magento\Customer\Model\CustomerFactory $customerFactory
	 * @param \Magento\Customer\Model\Visitor $visitor
	 */
	public function __construct
	(
		DepersonalizeChecker $depersonalizeChecker,
		\Magento\Framework\Session\SessionManagerInterface $session,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Customer\Model\Visitor $visitor,
		\Magento\Framework\Registry $registry
	) 
	{
		$this->session 				= $session;
		$this->customerSession 		= $customerSession;
		$this->customerFactory 		= $customerFactory;
		$this->visitor 				= $visitor;
		$this->depersonalizeChecker = $depersonalizeChecker;
		$this->registry				= $registry;
	}
	
	/**
	 * After generate Xml
	 *
	 * @param \Magento\Framework\View\LayoutInterface $subject
	 * @param \Magento\Framework\View\LayoutInterface $result
	 * @return \Magento\Framework\View\LayoutInterface
	 */
	public function afterGenerateXml(\Magento\Framework\View\LayoutInterface $subject, $result)
	{	
		return $result;
	}
}
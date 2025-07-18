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

namespace Anowave\Ec\Controller\Adminhtml\Consent;

use Anowave\Ec\Model\Consent as Consent;

class Delete extends \Magento\Backend\App\Action
{
	public function execute()
	{
		$id = $this->getRequest()->getParam('id');
		
		if (!($consent = $this->_objectManager->create(Consent::class)->load($id))) 
		{
			$this->messageManager->addErrorMessage(__('Unable to proceed. Please, try again.'));
			
			$resultRedirect = $this->resultRedirectFactory->create();
			
			return $resultRedirect->setPath('*/*/index', array('_current' => true));
		}
		try
		{
		    $consent->delete();
			
			$this->messageManager->addSuccessMessage(__('Consent deleted successfully'));
		} 
		catch (Exception $e) 
		{
			$this->messageManager->addErrorMessage(__('Error while trying to delete consent: '));
			
			$resultRedirect = $this->resultRedirectFactory->create();
			
			return $resultRedirect->setPath('*/*/index', array('_current' => true));
		}
		
		$resultRedirect = $this->resultRedirectFactory->create();
		
		return $resultRedirect->setPath('*/*/index', array('_current' => true));
	}
}
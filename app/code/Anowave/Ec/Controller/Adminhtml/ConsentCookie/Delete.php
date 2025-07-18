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

namespace Anowave\Ec\Controller\Adminhtml\ConsentCookie;

use Anowave\Ec\Model\ConsentCookie as ConsentCookie;

class Delete extends \Magento\Backend\App\Action
{
	public function execute()
	{
		$id = $this->getRequest()->getParam('id');
		
		if (!($cookie = $this->_objectManager->create(ConsentCookie::class)->load($id))) 
		{
			$this->messageManager->addErrorMessage(__('Unable to proceed. Please, try again.'));
			
			$resultRedirect = $this->resultRedirectFactory->create();
			
			return $resultRedirect->setPath('*/*/index', array('_current' => true));
		}
		try
		{
		    $cookie->delete();
			
			$this->messageManager->addSuccessMessage(__('Cookie deleted successfully'));
		} 
		catch (Exception $e) 
		{
			$this->messageManager->addErrorMessage(__('Error while trying to delete cookie: '));
			
			$resultRedirect = $this->resultRedirectFactory->create();
			
			return $resultRedirect->setPath('*/*/index', array('_current' => true));
		}
		
		$resultRedirect = $this->resultRedirectFactory->create();
		
		return $resultRedirect->setPath('*/*/index', array('_current' => true));
	}
}
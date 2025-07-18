<?php

/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Deliverydatepro\Controller\Multishipping\Checkout;

use Magento\Multishipping\Model\Checkout\Type\Multishipping\State;

class ShippingPost extends \Magento\Multishipping\Controller\Checkout\ShippingPost {

    /**
     * @return void
     */
    public function execute() {
        $shippingMethods = $this->getRequest()->getPost('shipping_method');

        try {
            $this->_eventManager->dispatch(
                    'checkout_controller_multishipping_shipping_post', ['request' => $this->getRequest(), 'quote' => $this->_getCheckout()->getQuote()]
            );
            $this->_getCheckout()->setShippingMethods($shippingMethods);

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->_proHelper = $objectManager->get('\Biztech\Deliverydatepro\Helper\Data');
            $this->_proHelper->saveMultiShippingData($this->getRequest()->getParams());
            $this->_getState()->setActiveStep(State::STEP_BILLING);
            $this->_getState()->setCompleteStep(State::STEP_SHIPPING);
            $this->_redirect('*/*/billing');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $this->_redirect('*/*/shipping');
        }
    }

}

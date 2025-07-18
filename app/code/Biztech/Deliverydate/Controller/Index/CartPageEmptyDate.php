<?php

namespace Biztech\Deliverydate\Controller\Index;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class CartPageEmptyDate extends Action {

    protected $resultJsonFactory;
    protected $checkoutSession;
    /**
     * Store constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
    Context $context, JsonFactory $resultJsonFactory, Session $checkoutSession
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute() {
        try {
            $quote = $this->checkoutSession->getQuote();

            $quote->setShippingArrivalDate(NULL);
            $quote->setShippingArrivalSlot(NULL);
            $quote->setShippingArrivalComments(NULL);
            $quote->setSameDayCharges(0);
            $quote->setDeliveryCharges(0);
            $quote->setCallBeforeDelivery(0);
            $quote->save();
            $result['result'] = 'success';
        } catch (\Exception $ex) {
            $result['result'] = 'error';
            $result['msg'] = $ex->getMessage();
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }

}

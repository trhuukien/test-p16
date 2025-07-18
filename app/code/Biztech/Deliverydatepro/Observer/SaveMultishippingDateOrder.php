<?php

namespace Biztech\Deliverydatepro\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SaveMultishippingDateOrder implements ObserverInterface {

    /**
     * @var \Biztech\Deliverydate\Helper\Data
     */
    protected $helper;

    public function __construct(
    \Biztech\Deliverydate\Helper\Data $data
    ) {
        $this->helper = $data;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer) {
        if ($this->helper->isEnable() && $this->helper->getConfigValue('deliverydate/deliverydate_configuration/on_multishipping', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $order = $observer->getEvent()->getData('order');
            $address = $observer->getEvent()->getData('address');


            $order->setShippingArrivalDate($address->getShippingArrivalDate())
                    ->setShippingArrivalSlot($address->getShippingArrivalSlot())
                    ->setDeliveryCharges($address->getDeliveryCharges())
                    ->setSameDayCharges($address->getSameDayCharges())
                    ->setShippingArrivalComments($address->getShippingArrivalComments())
                    ->setCallBeforeDelivery($address->getCallBeforeDelivery())
                    ->save();
        }
    }

}

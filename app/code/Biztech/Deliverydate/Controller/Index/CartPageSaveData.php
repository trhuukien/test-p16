<?php

namespace Biztech\Deliverydate\Controller\Index;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Directory\Model\Currency;

class CartPageSaveData extends Action {

    /**
     * Store constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Biztech\Deliverydate\Helper\Data $dataHelper
     * @param Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
            Context $context, JsonFactory $resultJsonFactory, \Biztech\Deliverydate\Helper\Data $dataHelper, \Magento\Framework\Pricing\Helper\Data $priceHelper, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone, \Magento\Framework\Stdlib\DateTime\DateTime $date, \Magento\Checkout\Model\Session $checkoutSession, Currency $Currency
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_bizHelper = $dataHelper;
        $this->_priceHelper = $priceHelper;
        $this->_timezone = $timezone;
        $this->_date = $date;
        $this->checkoutSession = $checkoutSession;
        $this->currency = $Currency;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute() {
        $result = array();
        try {
            $this->proHelper = $this->_bizHelper->objectManager()->get('\Biztech\Deliverydatepro\Helper\Data');
            $post = $this->getRequest()->getPostValue();
            $quote = $this->checkoutSession->getQuote();
            $deliveryDate = "";
            $deliveryComments="";
            if (isset($post['shipping_arrival_date'])) {
                $deliveryDate = $post['shipping_arrival_date'];
            }
            if ($deliveryDate != "" || $deliveryDate != null) {
                if (isset($post['shipping_arrival_slot']))
                    $deliverySlot = $post['shipping_arrival_slot'];
                if (isset($post['delivery_charges']))
                    $deliveryCharges = $post['delivery_charges'];
                if (isset($post['shipping_arrival_comment']))
                    $deliveryComments = $post['shipping_arrival_comment'];
                if (isset($post['call_me_before']))
                    $callBeforeDelivery = $post['call_me_before'];

                $isSameDayCharge = false;
                $deliveryDateArray = [];
                $finalAdditionalCharge = 0;
                $quote->setSameDayCharges(0);

                if ($this->_bizHelper->applyAdditionalCharge()) {
                    $dayWiseChargeType = $this->proHelper->dayWiseCharge();
                    if ($dayWiseChargeType == 1) {
                        foreach ($quote->getAllVisibleItems() as $_item) {
                            if ($this->proHelper->allowProductWiseCharge()) {
                                $product = $this->proHelper->getProduct($_item->getProductId());
                                $additionalCharge = $product->getAdditionalDeliveryCharge();
                                $finalAdditionalCharge += $additionalCharge;
                            }
                            $deliveryDateArray[] = $_item->getBuyRequest()->getData('shipping_arrival_date');
                        }
                        if ($this->_bizHelper->isEnableAtProductPage()) {
                            $currentDate = date('d-m-Y');
                            if (in_array($currentDate, $deliveryDateArray)) {
                                $sameDayCharges = $this->_bizHelper->addSameDayCharges($currentDate);
                                if ($sameDayCharges['addCharge'] === true) {
                                    $finalAdditionalCharge += $sameDayCharges['charges'];
                                }
                            }
                            $quote->setSameDayCharges($finalAdditionalCharge);
                        } else {
                            $sameDayCharges = $this->_bizHelper->addSameDayCharges($deliveryDate);
                            if ($sameDayCharges['addCharge'] === true) {
                                $finalAdditionalCharge += $sameDayCharges['charges'];
                            }
                            if ($deliveryDate != "" || $deliveryDate != null) {
                                $quote->setSameDayCharges($finalAdditionalCharge);
                            }
                        }
                    }
                    if ($dayWiseChargeType == 2) {
                        foreach ($quote->getAllVisibleItems() as $_item) {
                            if ($this->proHelper->allowProductWiseCharge()) {
                                $product = $this->proHelper->getProduct($_item->getProductId());
                                $additionalCharge = $product->getAdditionalDeliveryCharge();
                                $finalAdditionalCharge += $additionalCharge;
                            }
                            $deliveryDateArray[] = $_item->getBuyRequest()->getData('shipping_arrival_date');
                        }
                        if ($this->_bizHelper->isEnableAtProductPage()) {
                            foreach ($deliveryDateArray as $dateValue) {
                                $weekDayCharges = $this->proHelper->addWeekDayCharges($dateValue);
                                if ($weekDayCharges['addCharge'] === true) {
                                    $finalAdditionalCharge += $weekDayCharges['charges'];
                                }
                            }
                            $quote->setSameDayCharges($finalAdditionalCharge);
                        } else {
                            $weekDayCharges = $this->proHelper->addWeekDayCharges($deliveryDate);
                            if ($weekDayCharges['addCharge'] === true) {
                                $finalAdditionalCharge += $weekDayCharges['charges'];
                            }
                            if ($deliveryDate != "" || $deliveryDate != null) {
                                $quote->setSameDayCharges($finalAdditionalCharge);
                            }
                        }
                    }
                }

                $quote->setShippingArrivalDate($deliveryDate);
                $quote->setShippingArrivalSlot($deliverySlot);
                $quote->setShippingArrivalComments($deliveryComments);
                $quote->setCallBeforeDelivery($callBeforeDelivery);

                if ($deliveryCharges == "" | $deliveryCharges == null) {
                    $deliveryCharges = (float) 0;
                } else {
                    $deliveryDateView = $this->_bizHelper->getDeliveryDateView();
                    if ($deliveryDateView == 1) {
                        $deliveryCharges = $this->currency->format($deliveryCharges, ['display' => \Magento\Framework\Currency\Data\Currency::NO_SYMBOL], false);
                    } else {
                        $deliveryCharges = (float) $deliveryCharges;
                    }
                }
                $quote->setDeliveryCharges($deliveryCharges);
                $quote->save();
                $result['result'] = 'success';
            }
        } catch (\Exception $ex) {
            $result['result'] = 'error';
            $result['msg'] = $ex->getMessage();
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }

}

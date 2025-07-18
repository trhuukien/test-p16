<?php

namespace Biztech\Deliverydate\Controller\Index;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Updatedeliverydate extends Action {

    /**
     * Store constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Magento\Sales\Model\Order $order
     */
    public function __construct(
        Context $context, 
        JsonFactory $resultJsonFactory, 
        \Magento\Sales\Model\Order $order
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_order = $order;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute() {
        $post = $this->getRequest()->getPostValue();
        $selectedDate = str_replace("/", "-", $post['selectedDate']);
        $time = strtotime($selectedDate);
        $selected_date = date('d-m-Y', $time);
        $selected_timeslot = $post['selectedTimeslot'];
        $orders = $this->_order->load($post['orderId']);
        $orders->setShippingArrivalDate($selected_date);
        if($selected_timeslot!='No Timeslot available') {
            $orders->setShippingArrivalSlot($selected_timeslot);
        } else {
            $selected_timeslot = null;
        }
        $orders->save();
        $result = array('date'=>$selected_date.' '.$selected_timeslot,'status'=>'Success');
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }

}

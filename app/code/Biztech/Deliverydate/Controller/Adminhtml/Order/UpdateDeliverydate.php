<?php

namespace Biztech\Deliverydate\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * SaveDeliverydateInfo
 */
class UpdateDeliverydate extends Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
    Context $context, JsonFactory $resultJsonFactory, \Magento\Sales\Model\Order $order, ManagerInterface $messageManager
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_order = $order;
        $this->_messageManager = $messageManager;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute() {
        try {
            $post = $this->getRequest()->getParams();
            $selectedDate = str_replace("/", "-", $post['selectedDate']);
            $time = strtotime($selectedDate);
            $selected_date = date('d-m-Y', $time);
            $selected_timeslot = $post['selectedTimeslot'];
            $orders = $this->_order->load($post['orderId']);
            $orders->setShippingArrivalDate($selected_date);
            $shippingArrivalComments = $post['comments'];
            if ($shippingArrivalComments != '')
                $orders->setShippingArrivalComments($shippingArrivalComments);
            if ($selected_timeslot != 'No Timeslot available') {
                $orders->setShippingArrivalSlot($selected_timeslot);
            } else {
                $selected_timeslot = null;
            }
            $orders->save();
            $this->_messageManager->addSuccess('Order delivery date updated successfully.');
            $result = array('date' => $selected_date . ' ' . $selected_timeslot, 'status' => 'Success');
        } catch (\Exception $e) {
            $result = array('date' => $selected_date . ' ' . $selected_timeslot, 'status' => 'Error');
            $this->_messageManager->addError($e->getMessage());
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }

}

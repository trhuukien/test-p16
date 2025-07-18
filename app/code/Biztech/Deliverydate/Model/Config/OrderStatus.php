<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class OrderStatus implements ArrayInterface {

    protected $orderStatusCollection;
    protected $_orderStatusCollection;

    public function __construct(
    \Magento\Sales\Model\ResourceModel\Order\Status\Collection $orderStatusCollection
    ) {
        $this->_orderStatusCollection = $orderStatusCollection;
    }

    public function toOptionArray() {
        $statusCollection = $this->_orderStatusCollection;
        return $statusCollection->toOptionArray();
    }

}

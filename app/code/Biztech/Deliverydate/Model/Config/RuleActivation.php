<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class RuleActivation implements ArrayInterface {

    public function toOptionArray() {
        return [
            "status" => __('Order Status'),
            "date" => __('Day')
        ];
    }

}

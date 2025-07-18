<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class TimeDisplayMode implements ArrayInterface {

    public function toOptionArray() {
        return [
            "radio" => __('Radio Button'),
            "dropdown" => __('Dropdown'),
            "button" => __('Button'),
        ];
    }

}

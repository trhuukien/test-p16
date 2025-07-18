<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class DateIntervalUpdate implements ArrayInterface {

    public function toOptionArray() {
        return [
            "1" => __("1 day"),
            "2" => __("2 days"),
            "3" => __("3 days"),
            "4" => __("4 days"),
            "5" => __("5 days"),
            "6" => __("6 days"),
            "7" => __("7 days"),
            "14" => __("14 days"),
            "30" => __("30 days"),
        ];
    }

}

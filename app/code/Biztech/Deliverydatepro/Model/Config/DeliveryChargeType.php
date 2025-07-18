<?php

namespace Biztech\Deliverydatepro\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class DeliveryChargeType implements ArrayInterface {

    const SAME_DAY = 1;
    const WEEK = 2;

    public function toOptionArray() {
        return [
            self::SAME_DAY => __('Same Day Charge (Global)'),
            self::WEEK => __('Per Day Of Week'),
        ];
    }

}

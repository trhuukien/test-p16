<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class ShowAt implements ArrayInterface {

    const TYPE_SHIPPING_PAGE = 1;

    public function toOptionArray() {
        return [
            self::TYPE_SHIPPING_PAGE => __('Checkout Shipping Method'),
        ];
    }

}

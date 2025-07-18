<?php

namespace Biztech\Deliverydatepro\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class DeliveryOption implements ArrayInterface {

    const TYPE_GLOBAL = 1;
    const TYPE_PRODUCT_PAGE = 2;

    public function toOptionArray() {
        return [
            self::TYPE_GLOBAL => __('Global'),
            self::TYPE_PRODUCT_PAGE => __('Product level')
        ];
    }

}

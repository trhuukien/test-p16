<?php

namespace Biztech\Deliverydatepro\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class Months implements ArrayInterface {

    public function toOptionArray() {
        return [
            '1' => __('January'),
            '2' => __('February'),
            '3' => __('March'),
            '4' => __('April'),
            '5' => __('May'),
            '6' => __('June'),
            '7' => __('July'),
            '8' => __('August'),
            '9' => __('September'),
            '10' => __('October'),
            '11' => __('November'),
            '12' => __('December'),
        ];
    }

}

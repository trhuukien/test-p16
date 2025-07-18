<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class DateDisplayMode implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            "calendar" => __('Calendar'),
            "radio" => __('Radio Button'),
            "dropdown" => __('Dropdown'),
            "button" => __('Button'),
        ];
    }
}

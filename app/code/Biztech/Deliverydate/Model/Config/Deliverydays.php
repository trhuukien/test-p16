<?php

namespace Biztech\Deliverydate\Model\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Deliverydays extends AbstractSource
{
    public function getAllOptions()
    {
        $options = [
            ['value' => '', 'label' => __('No Day')],
            ['value' => 0, 'label' => __('Sunday')],
            ['value' => 1, 'label' => __('Monday')],
            ['value' => 2, 'label' => __('Tuesday')],
            ['value' => 3, 'label' => __('Wednesday')],
            ['value' => 4, 'label' => __('Thursday')],
            ['value' => 5, 'label' => __('Friday')],
            ['value' => 6, 'label' => __('Saturday')],
        ];

        return $options;
    }
}

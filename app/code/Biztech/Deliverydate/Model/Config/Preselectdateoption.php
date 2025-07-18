<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Biztech\Deliverydate\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class Preselectdateoption implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            '' => __('Please Select'),
            '1' => __('1 Day After'),
            '2' => __('2 Days After'),
            '3' => __('3 Days After'),
            '4' => __('4 Days After'),
            '5' => __('5 Days After'),
        ];
    }
}

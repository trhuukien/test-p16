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

class Dateformat implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'd/M/Y' => __('d/M/Y'),
            'M/d/y' => __('M/d/y'),
            'd-M-Y' => __('d-M-Y'),
            'M-d-y' => __('M-d-y'),
            'd.M.Y' => __('d.M.Y'),
            'M.d.y' => __('M.d.y'),
            'd F Y' => __('d F Y'),
            'l j F' => __('l j F'),
        ];
    }
}

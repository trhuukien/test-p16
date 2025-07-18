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

class Cutoffhour extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource {
//    protected $_options = null;

    /**
     * Get possible sharing configuration options.
     *
     * @return array
     */
    public function getAllOptions() {
        if (empty($this->_options)) {
            $this->_options = array();
            $required = array(
                'label' => (string) '-- Please Select --',
                'value' => ''
            );
            array_push($this->_options, $required);
            for ($i = 0; $i <= 23; $i++) {
                $data = [
                    'label' => __($i),
                    'value' => $i,
                ];
                array_push($this->_options, $data);
            }
        }
        return $this->_options;
    }

}

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
use Magento\Framework\ObjectManagerInterface;

class EmailTemplateList implements ArrayInterface {

    protected $_objectManager;
    public function __construct(ObjectManagerInterface $objectmanager) {
        $this->_objectManager = $objectmanager;
    }

    public function toOptionArray() {
        $alltemplele = $this->_objectManager->get('Magento\Email\Model\Template\Config');
        $alltemplele_data = $alltemplele->getAvailableTemplates();
        $templates = [
            ['value' => '', 'label' => __('-- Please Select --')],
        ];
        foreach ($alltemplele_data as $group => $options):
            $label = $options['label'];
            if (is_array($label)) {
                $templates[] = ['label' => $label->getText(), 'value' => $options['value']];
            } else {
                $templates[] = ['label' => $label, 'value' => $options['value']];
            }
        endforeach;
        return $templates;
    }

}

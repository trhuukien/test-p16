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

namespace Biztech\Deliverydatepro\Block\Adminhtml;

use Magento\Backend\Block\Widget\Tabs;

class Deliverydateordercalendar extends Tabs
{

    protected function _construct()
    {
        parent::_construct();
        $this->setId('deliverydatecalendar');
        $this->setDestElementId('deliverydatecalendar_content');
        $this->setTemplate('Biztech_Deliverydatepro::ordersdeliverydate.phtml');
    }
}

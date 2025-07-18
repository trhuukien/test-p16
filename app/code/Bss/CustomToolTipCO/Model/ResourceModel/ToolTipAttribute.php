<?php
namespace Bss\CustomToolTipCO\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ToolTipAttribute extends AbstractDb
{
    /**
     * construct
     */
    public function _construct()
    {
        $this->_init('bss_tooltip_product_attribute', 'id');
    }

}

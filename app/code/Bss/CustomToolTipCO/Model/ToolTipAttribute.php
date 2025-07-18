<?php
namespace Bss\CustomToolTipCO\Model;

use Magento\Framework\Model\AbstractModel;

class ToolTipAttribute extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public function _construct()
    {
        $this->_init(\Bss\CustomToolTipCO\Model\ResourceModel\ToolTipAttribute::class);
    }

}

<?php
namespace Bss\CustomToolTipCO\Model\ResourceModel\ToolTipAttribute;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function _construct()
    {
        $this->_init(
            \Bss\CustomToolTipCO\Model\ToolTipAttribute::class,
            \Bss\CustomToolTipCO\Model\ResourceModel\ToolTipAttribute::class
        );
    }

}

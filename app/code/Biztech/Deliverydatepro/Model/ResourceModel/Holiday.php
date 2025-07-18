<?php
/**
 * Copyright © 2015 Biztech. All rights reserved.
 */
namespace Biztech\Deliverydatepro\Model\ResourceModel;

/**
 * Holiday resource
 */
class Holiday extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('deliverydatepro_holiday', 'id');
    }

  
}

<?php
namespace Biztech\Deliverydatepro\Block\Adminhtml;
class Holiday extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_holiday';/*block grid.php directory*/
        $this->_blockGroup = 'Biztech_Deliverydatepro';
        $this->_headerText = __('Holiday');
        $this->_addButtonLabel = __('Add New Holiday'); 
        parent::_construct();
		
    }
}

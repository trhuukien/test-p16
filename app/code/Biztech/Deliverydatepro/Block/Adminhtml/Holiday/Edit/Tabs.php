<?php
namespace Biztech\Deliverydatepro\Block\Adminhtml\Holiday\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_holiday_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Holiday Information'));
    }
}
<?php

namespace Biztech\Deliverydatepro\Block\Adminhtml\Config\Form\Field;

use Biztech\Deliverydatepro\Model\Config\Weekdayoff;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class ChargeDayOfWeek extends AbstractFieldArray {

    protected $_dayoff;
    protected $_dayoffRenderer;

    /**
     * @param Context $context
     * @param Starttime $time
     * @param array $data
     */
    public function __construct(
    Context $context, Weekdayoff $dayoff, array $data = []
    ) {
        $this->_dayoff = $dayoff;
        parent::__construct($context, $data);
    }

    /**
     * Prepare to render.
     */
    protected function _prepareToRender() {
        $this->addColumn('sort_period', ['label' => __('Sort'), 'style' => 'width:50px', 'class' => 'required-entry validate-digits']);
        $this->addColumn('day', ['label' => __('Day'), 'renderer' => $this->_getDayChargeRenderer(), 'style' => 'width:194px']);

        $this->addColumn('price', ['label' => __('Price'), 'style' => 'width:100px', 'class' => 'validate-number']);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Day');
    }

    /**
     * Retrieve start column renderer.
     *
     * @return StartTimegroup
     */
    protected function _getDayChargeRenderer() {
        if (!$this->_dayoffRenderer) {
            $this->_dayoffRenderer = $this->getLayout()->createBlock(
                    'Biztech\Deliverydate\Block\Adminhtml\Config\Form\Renderer\Ddselect', '', ['data' => ['is_render_to_js_template' => true]]
            );

            $this->_dayoffRenderer->setClass('dayoff');
            $this->_dayoffRenderer->setStyle('width:115px;');
            $this->_dayoffRenderer->setOptions($this->_dayoff->toDays());
        }

        return $this->_dayoffRenderer;
    }

    /**
     * Prepare existing row data object.
     *
     * @param \Magento\Framework\DataObject $row
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row) {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getDayChargeRenderer()->calcOptionHash($row->getData('day'))] = 'selected="selected"';
        $row->setData(
                'option_extra_attrs', $optionExtraAttr
        );
    }

}

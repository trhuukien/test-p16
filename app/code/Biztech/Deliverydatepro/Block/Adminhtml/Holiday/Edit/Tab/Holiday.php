<?php

namespace Biztech\Deliverydatepro\Block\Adminhtml\Holiday\Edit\Tab;

class Holiday extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Store\Model\System\Store $systemStore, \Biztech\Deliverydatepro\Model\Config\Months $months, array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        $this->months = $months;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm() {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('deliverydatepro_holiday');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Holiday')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

        $fieldset->addField(
                'title', 'text', array(
            'name' => 'title',
            'label' => __('Holiday Title'),
            'title' => __('Holiday Title'),
            'required' => true,
                )
        );

        $main = $fieldset->addField(
                'is_annual', 'select', array(
            'name' => 'is_annual',
            'label' => __('Annual'),
            'title' => __('Annual'),
            'required' => true,
            'options' => ['1' => __('Yes'), '0' => __('No')],
                )
        );

        $fieldset->addField(
                'day', 'text', array(
            'name' => 'day',
            'label' => __('Day'),
            'title' => __('Day'),
            'required' => true,
            'class' => 'validate-digits-range digits-range-1-31 validate-digits',
            'note' => 'Date range will be 1 to 31',
                )
        );
        $fieldset->addField(
                'month', 'select', array(
            'name' => 'month',
            'label' => __('Month'),
            'title' => __('Month'),
            'required' => true,
            'values' => $this->months->toOptionArray()
                )
        );

        $year = $fieldset->addField(
                'year', 'text', array(
            'name' => 'year',
            'label' => __('Year'),
            'title' => __('Year'),
            'required' => true,
            'class' => 'number validate-greater-than-zero validate-digits',
                )
        );

        $this->setChild(
                'form_after', $this->getLayout()->createBlock('\Magento\Backend\Block\Widget\Form\Element\Dependence')
                        ->addFieldMap($main->getHtmlId(), $main->getName())
                        ->addFieldMap($year->getHtmlId(), $year->getName())
                        ->addFieldDependence($year->getName(), $main->getName(), 0)
        );

        $fieldset->addField(
                'status', 'select', array(
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
            'required' => true,
            'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
                )
        );

        /* {{CedAddFormField}} */

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return __('Holiday');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Holiday');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden() {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }

}

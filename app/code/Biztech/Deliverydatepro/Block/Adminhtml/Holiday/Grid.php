<?php

namespace Biztech\Deliverydatepro\Block\Adminhtml\Holiday;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Biztech\Deliverydatepro\Model\ResourceModel\Holiday\Collection $collectionFactory, \Magento\Framework\Module\Manager $moduleManager, \Biztech\Deliverydatepro\Model\Config\Months $months, array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->months = $months;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();

        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return Store
     */
    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection() {
        try {
            $collection = $this->_collectionFactory->load();
            $this->setCollection($collection);

            parent::_prepareCollection();

            return $this;
        } catch (Exception $e) {
            $e->getMessage();
            return;
        }
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                        'websites', 'catalog_product_website', 'website_id', 'product_id=entity_id', null, 'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns() {
        $this->addColumn(
                'id', [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
                ]
        );
        $this->addColumn(
                'title', [
            'header' => __('Holiday Title'),
            'index' => 'title',
            'class' => 'title'
                ]
        );
        $this->addColumn(
                'day', [
            'header' => __('Day'),
            'index' => 'day',
            'class' => 'day'
                ]
        );
        $this->addColumn(
                'month', [
            'header' => __('Month'),
            'index' => 'month',
            'class' => 'month',
            'type' => 'options',
            'options' => $this->months->toOptionArray()
                ]
        );
        $this->addColumn(
                'year', [
            'header' => __('Year'),
            'index' => 'year',
            'class' => 'year'
                ]
        );
        $this->addColumn(
                'is_annual', [
            'header' => __('Annual'),
            'index' => 'is_annual',
            'class' => 'is_annual',
            'type' => 'options',
            'options' => array(1 => 'Yes', 0 => 'No')
                ]
        );
        $this->addColumn(
                'status', [
            'header' => __('Status'),
            'index' => 'status',
            'class' => 'status',
            'type' => 'options',
            'options' => array(1 => 'Enable', 0 => 'Disable')
                ]
        );
        /* {{CedAddGridColumn}} */

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
                'delete', array(
            'label' => __('Delete'),
            'url' => $this->getUrl('deliverydatepro/*/massDelete'),
            'confirm' => __('Are you sure you want to delete holiday(s)?')
                )
        );

        $this->getMassactionBlock()->addItem('status', array(
            'label' => __('Change Status'),
            'url' => $this->getUrl('deliverydatepro/*/massStatus', array('_current' => true)),
            'additional' => [
                'visibility' => [
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'options' => ['1' => __('Enabled'), '0' => __('Disabled')],
                ]
            ]
        ));

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('deliverydatepro/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row) {
        return $this->getUrl(
                        'deliverydatepro/*/edit', ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }

}

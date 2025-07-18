<?php

namespace Biztech\Deliverydatepro\Model\Config;

class ShippingMethod implements \Magento\Framework\Option\ArrayInterface {

    /**
     * Customer groups options array
     *
     * @var null|array
     */
    protected $_options;

    /**
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $shipconfig;
    protected $scopeConfig; 

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupColl
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Shipping\Model\Config $shipconfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->shipconfig = $shipconfig;
    }

    /**
     *
     * @return array
     */
    public function getShippingMethods() {
        $activeCarriers = $this->shipconfig->getActiveCarriers();

        foreach ($activeCarriers as $carrierCode => $carrierModel) {
            $options = array();

            if ($carrierMethods = $carrierModel->getAllowedMethods()) {
                foreach ($carrierMethods as $methodCode => $method) {
                    $code = $carrierCode . '_' . $methodCode;
                    $options[] = array('value' => $code, 'label' => $method);
                }
                $carrierTitle = $this->scopeConfig
                        ->getValue('carriers/' . $carrierCode . '/title');
            }

            $methods[] = array('value' => $options, 'label' => $carrierTitle);
        }

        return $methods;
    }

    /**
     * Retrieve customer groups as array
     *
     * @return array
     */
    public function toOptionArray() {
        if (!$this->_options) {
            $this->_options = $this->getShippingMethods();
        }
        return $this->_options;
    }

}

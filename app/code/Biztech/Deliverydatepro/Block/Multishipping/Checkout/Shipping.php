<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
//declare(strict_types=1);

namespace Biztech\Deliverydatepro\Block\Multishipping\Checkout;

use Magento\Framework\Pricing\PriceCurrencyInterface;

//use Magento\Quote\Model\Quote\Address;
//use Magento\Store\Model\ScopeInterface;

/**
 * Mustishipping checkout shipping
 *
 * @api
 * @author     Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class Shipping extends \Magento\Multishipping\Block\Checkout\Shipping {
    /**
     * @var \Magento\Framework\Filter\DataObject\GridFactory
     */
//    protected $_filterGridFactory;
//
//    /**
//     * @var \Magento\Tax\Helper\Data
//     */
//    protected $_taxHelper;
//
//    /**
//     * @var PriceCurrencyInterface
//     */
//    protected $priceCurrency;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Filter\DataObject\GridFactory $filterGridFactory
     * @param \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Filter\DataObject\GridFactory $filterGridFactory, \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping, \Magento\Tax\Helper\Data $taxHelper, PriceCurrencyInterface $priceCurrency, array $data = []
    ) {
        parent::__construct($context, $filterGridFactory, $multishipping, $taxHelper, $priceCurrency, $data);
    }

    protected function _prepareLayout() {
        $this->pageConfig->getTitle()->set(
                __('Shipping Methods') . ' - ' . $this->pageConfig->getTitle()->getDefault()
        );
        return parent::_prepareLayout();
    }

}

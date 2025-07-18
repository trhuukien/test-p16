<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Biztech\Deliverydate\Block\Order;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Address;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use    \Biztech\Deliverydate\Helper\Data;

use \Biztech\Deliverydatepro\Helper\Data as ProHelper;
use Magento\Sales\Api\OrderRepositoryInterface;
class Info extends Template
{

    /**
     * @var string
     */
    protected $_template = 'Biztech_Deliverydate::order/info.phtml';

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    /**
     * @var AddressRenderer
     */
    protected $addressRenderer;

    /**
     * @var Biztech\Deliverydate\Helper/Data;
     */
    public $deliveryDateHelper;
    protected $orderRepository;

    protected $proHelper;

    /**
     * @param TemplateContext $context
     * @param Registry $registry
     * @param PaymentHelper $paymentHelper
     * @param AddressRenderer $addressRenderer
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        PaymentHelper $paymentHelper,
        AddressRenderer $addressRenderer,
        Data $helper,
        OrderRepositoryInterface $orderRepository,
        ProHelper $proHelper,
        array $data = []
    ) {
        $this->addressRenderer = $addressRenderer;
        $this->paymentHelper = $paymentHelper;
        $this->coreRegistry = $registry;
        $this->_isScopePrivate = true;
        $this->deliveryDateHelper = $helper;
        $this->orderRepository = $orderRepository;
        $this->proHelper = $proHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getPaymentInfoHtml()
    {
        return $this->getChildHtml('payment_info');
    }

    /**
     * Returns string with formatted address.
     *
     * @param Address $address
     *
     * @return null|string
     */
    public function getFormattedAddress(Address $address)
    {
        return $this->addressRenderer->format($address, 'html');
    }

    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Order # %1', $this->getOrder()->getRealOrderId()));
        $infoBlock = $this->paymentHelper->getInfoBlock($this->getOrder()->getPayment(), $this->getLayout());
        $this->setChild('payment_info', $infoBlock);
    }

    /**
     * Retrieve current order model instance.
     *
     * @return \Magento\Sales\Model\Order
     */

    public function getShippingArrivalDate()
    {
        $orderId =  $this->getOrder()->getRealOrderId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orders = $objectManager->create('Magento\Sales\Model\Order')->load($orderId);
        return $orders->getShippingArrivalDate();
        
    }

    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    public function getDeliveryDateConfig()
    {
        $shippingArrivalDate = $this->getShippingArrivalDate();
        return $this->deliveryDateHelper->getDeliverydateConfig($shippingArrivalDate);
    }


}

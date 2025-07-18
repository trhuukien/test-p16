<?php

namespace Medeka\CheckmoInvoice\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Medeka\CheckmoInvoice\Model\CreateInvoice;
use Magento\Sales\Model\Order\Payment\Transaction as PaymentTransaction;

class InvoiceObserver implements ObserverInterface
{
    protected $createInvoice;

    public function __construct(CreateInvoice $createInvoice)
    {
        $this->createInvoice = $createInvoice;
    }

    public function execute(Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }

        foreach ($orderIds as $orderId) {
            $order = $this->createInvoice->getOrder($orderId);
            if ($order && $order->getPayment()->getMethod() == 'checkmo') {
                $this->createInvoice->execute($orderId);
            }
        }
    }
}

<?php

namespace Medeka\CheckmoInvoice\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class CreateInvoice
{
    protected $orderRepository;
    protected $invoiceService;
    protected $transaction;
    protected $invoiceSender;
    protected $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        InvoiceSender $invoiceSender,
        Transaction $transaction,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->invoiceSender = $invoiceSender;
        $this->logger = $logger;
    }

    public function getOrder($orderId)
    {
        return $this->orderRepository->get($orderId);
    }

    public function execute($orderId)
    {
        try {
            $order = $this->getOrder($orderId);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->register();
                
                // Log current order status
                $this->logger->info('Current order state: ' . $order->getState());
                $this->logger->info('Current order status: ' . $order->getStatus());

                // Set order status to Processing
                $order->setState(Order::STATE_PROCESSING)->setStatus(Order::STATE_PROCESSING);
                
                $transactionSave = $this->transaction
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
                $transactionSave->save();

                $this->invoiceSender->send($invoice);

                $order->addCommentToStatusHistory(
                    __('Notified customer about invoice creation #%1.', $invoice->getId())
                )->setIsCustomerNotified(true)->save();

                // Log new order status
                $this->logger->info('New order state: ' . $order->getState());
                $this->logger->info('New order status: ' . $order->getStatus());

                $this->logger->info(sprintf('Invoice #%s created for order #%s.', $invoice->getId(), $order->getId()));
            } else {
                $this->logger->warning(sprintf('Order #%s cannot be invoiced.', $order->getId()));
            }
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error creating invoice for order #%s: %s', $orderId, $e->getMessage()));
        }
    }
}

<?php

namespace Biztech\Deliverydatepro\Controller\Adminhtml\Order;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;

class Calendar extends Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $resultPage;
    protected $_layoutFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
    Context $context, PageFactory $resultPageFactory, LayoutFactory $layoutFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page.
     */
    public function execute() {
        /* $resultPage = $this->resultPageFactory->create();
          $layout = $this->_layoutFactory->create();
          $output = $layout->createBlock('Biztech\Deliverydate\Block\Adminhtml\Dashboard\Deliverydatecalendar')->toHtml();
          $this->getResponse()->appendBody($output); */

        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Biztech_Deliverydatepro::calendar');
        $this->resultPage->getConfig()->getTitle()->set((__('Delivery Date Calendar')));
        return $this->resultPage;
    }

}

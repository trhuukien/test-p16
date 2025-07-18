<?php

namespace Biztech\Deliverydatepro\Controller\Adminhtml\Holiday;

class Edit extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Edit constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');

        $model = $this->_objectManager->create('Biztech\Deliverydatepro\Model\Holiday');

        $registryObject = $this->_objectManager->get('Magento\Framework\Registry');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This holiday no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $registryObject->register('deliverydatepro_holiday', $model);
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? __('Edit Holiday "') . $model->getTitle() . '"' : __('New Holiday'));
        $this->_view->renderLayout();
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction() {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_Deliverydatepro::holiday')
                ->addBreadcrumb(__('Holiday'), __('Holiday'))
                ->addBreadcrumb(__('Manage Holiday'), __('Manage Holiday'));
        return $resultPage;
    }

}

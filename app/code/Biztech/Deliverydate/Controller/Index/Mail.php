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

namespace Biztech\Deliverydate\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\ObjectManagerInterface;

class Mail extends \Magento\Framework\App\Action\Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory resultPageFactory
     */
    public function __construct(
    Context $context, ObjectManagerInterface $objectmanager, PageFactory $resultPageFactory
    ) {
        $this->_objectManager = $objectmanager;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page.
     */
    public function execute() {
        $alltemplele = $this->_objectManager->get('Biztech\Deliverydate\Helper\Data');
        $alltemplele_data = $alltemplele->sendEmailNotification();
        
        //return $this->resultPageFactory->create();
    }

}

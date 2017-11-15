<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

/**
 * Brand Index action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Index extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->_resultForwardFactory->create();
            $resultForward->forward('grid');

            return $resultForward;
        }

        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}

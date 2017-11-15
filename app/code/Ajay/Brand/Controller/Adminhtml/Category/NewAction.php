<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

/**
 * New Brand Action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class NewAction extends \Ajay\Brand\Controller\Adminhtml\Category
{
    /**
     * Dispatch request
     */
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ajay_Brand::save');
    }
}

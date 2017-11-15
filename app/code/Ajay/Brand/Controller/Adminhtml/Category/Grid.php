<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

/**
 * Brand Grid action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Grid extends \Ajay\Brand\Controller\Adminhtml\Category
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        return $this->_resultLayoutFactory->create();
    }
}

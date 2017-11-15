<?php
/**
 * Brand attribute index controller
 */

namespace Ajay\Brand\Controller\Adminhtml\Category\Attribute;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory) 
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        
    }
    
    /*
     * Index action.
     * 
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /* @var Magento\Backend\Model\View\Result\Page $resultPage*/
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ajay_Brand::category_attributes');
        $resultPage->addBreadcrumb(__('Brand Category Attribute'), __('Brand Category'));
        $resultPage->addBreadcrumb(__('Manage Brand Attribute'), __('Brand Category Attributes'));
        $resultPage->getConfig()->getTitle()->prepend(__('Brand Category Attributes'));
        return $resultPage;
    }
    
    /*
     * Is user allowed to view manage customer attribute grid.
     * 
     * @return bool
     */
    protected function _isAllowed() 
    {
        return $this->_authorization->isAllowed('Ajay_Brand::category_attributes');
    }
}
<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

/**
 * Mass Delete Brand action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class MassDelete extends \Ajay\Brand\Controller\Adminhtml\Category
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $categoryIds = $this->getRequest()->getParam('category');
        
        if (!is_array($categoryIds) || empty($categoryIds)) {
            $this->messageManager->addError(__('Please select category(s).'));
        } else {
            $categoryCollection = $this->_categoryCollectionFactory->create()
                ->addFieldToFilter('entity_id', ['in' => $categoryIds]);
            try {
                foreach ($categoryCollection as $category) {
                    $category->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 category(s) have been deleted.', count($categoryIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}

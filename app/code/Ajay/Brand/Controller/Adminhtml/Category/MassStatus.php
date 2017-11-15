<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

/**
 * Mass Status action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class MassStatus extends \Ajay\Brand\Controller\Adminhtml\Category
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $categoryIds = $this->getRequest()->getParam('category');
        $status = $this->getRequest()->getParam('status');
        
        if (!is_array($categoryIds) || empty($categoryIds)) {
            $this->messageManager->addError(__('Please select category(s).'));
        } else {
            try {
                $categoryCollection = $this->_categoryCollectionFactory->create()
                    ->addFieldToFilter('entity_id', ['in' => $categoryIds]);

                foreach ($categoryCollection as $category) {
                    $category->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 category(s) status have been changed.', count($categoryIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}

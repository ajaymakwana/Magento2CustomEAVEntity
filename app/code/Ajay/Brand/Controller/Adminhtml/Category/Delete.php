<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

/**
 * Delete Brand action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Delete extends \Ajay\Brand\Controller\Adminhtml\Category
{
    /**
     * Dispatch request
     */
    public function execute()
    {
        $categoryId = $this->getRequest()->getParam(static::PARAM_ID);
        try {
            $brand = $this->_categoryFactory->create()->setId($categoryId);
            $brand->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}

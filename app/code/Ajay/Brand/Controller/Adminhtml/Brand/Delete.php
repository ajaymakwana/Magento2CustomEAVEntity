<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

/**
 * Delete Brand action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Delete extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * Dispatch request
     */
    public function execute()
    {
        $brandId = $this->getRequest()->getParam(static::PARAM_ID);
        try {
            $brand = $this->_brandFactory->create()->setId($brandId);
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

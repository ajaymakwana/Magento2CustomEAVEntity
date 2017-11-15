<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

/**
 * Mass Status action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class MassStatus extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $brandIds = $this->getRequest()->getParam('brand');
        $status = $this->getRequest()->getParam('status');
        
        if (!is_array($brandIds) || empty($brandIds)) {
            $this->messageManager->addError(__('Please select brand(s).'));
        } else {
            try {
                $brandCollection = $this->_brandCollectionFactory->create()
                    ->addFieldToFilter('entity_id', ['in' => $brandIds]);

                foreach ($brandCollection as $brand) {
                    $brand->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 brand(s) status have been changed.', count($brandIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}

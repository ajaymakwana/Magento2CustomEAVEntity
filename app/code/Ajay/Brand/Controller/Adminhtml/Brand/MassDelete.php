<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

/**
 * Mass Delete Brand action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class MassDelete extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $brandIds = $this->getRequest()->getParam('brand');
        
        if (!is_array($brandIds) || empty($brandIds)) {
            $this->messageManager->addError(__('Please select brand(s).'));
        } else {
            $brandCollection = $this->_brandCollectionFactory->create()
                ->addFieldToFilter('entity_id', ['in' => $brandIds]);
            try {
                foreach ($brandCollection as $brand) {
                    $brand->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 brand(s) have been deleted.', count($brandIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}

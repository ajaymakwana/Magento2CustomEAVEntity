<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

/**
 * Edit Brand action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Edit extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * @var StoreFactory
     */
    protected $storeFactory;
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $storeId = (int)$this->getRequest()->getParam('store');

        $store = $this->getStoreFactory()->create();
        $store->load($this->getRequest()->getParam('store', 0));

        $id = $this->getRequest()->getParam('id');
        $brand = $this->_objectManager->create('Ajay\Brand\Model\Brand');
        $brand->setStoreId($storeId);
        $brand->setAttributeSetId($brand->getDefaultAttributeSetId());
        //$brand = $this->_brandFactory->create();
        //$brand->setStoreId($this->getRequest()->getParam('store', 0));
        if ($id) {
            $brand->load($id);
            if (!$brand->getId()) {
                $this->messageManager->addError(__('This brand no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $brand->setData($data);
        }

        $this->_coreRegistry->register('current_brand', $brand);
        $this->_coreRegistry->register('current_store', $store);
        return $resultPage;
    }

    /**
     * @return StoreFactory
     */
    private function getStoreFactory()
    {
        if (null === $this->storeFactory) {
            $this->storeFactory = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Store\Model\StoreFactory');
        }
        return $this->storeFactory;
    }
}

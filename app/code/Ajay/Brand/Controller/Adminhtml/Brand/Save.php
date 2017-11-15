<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

use Ajay\Brand\Model\Brand;

/**
 * Save Brand action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Save extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $store = $this->getStoreManager()->getStore($storeId);
        $this->getStoreManager()->setCurrentStore($store->getCode());
        $redirectBack = $this->getRequest()->getParam('back', false);
        $brandId = $productId = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $formPostValues = $this->getRequest()->getPostValue();
       
        if (isset($formPostValues)) {
            $brandData = $formPostValues['brand'];
            //unset($brandData['image']);
            $brandModel = $this->_brandFactory->create();
            $brandModel->setStoreId($this->getRequest()->getParam('store', 0));
            $brandModel->load($brandId);
            $brandModel->addData($brandData);

            $brandModel->setAttributeSetId($brandModel->getDefaultAttributeSetId());
            $brandModel->setEntityTypeId(10);
            try {
                /**
                 * Check "Use Default Value" checkboxes values
                 */
                if (isset($formPostValues['use_default']) && !empty($formPostValues['use_default'])) {
                    foreach ($formPostValues['use_default'] as $attributeCode => $attributeValue) {
                        if ($attributeValue) {
                            $brandModel->setData($attributeCode, null);
                        }
                    }
                }
               
                /* Prepare relative links */
                if (isset($formPostValues['links'])) {
                    $links = isset($formPostValues['links']) ? $formPostValues['links'] : null;
                    if ($links && is_array($links)) {
                        foreach (['product'] as $linkType) {
                            if (!empty($links[$linkType]) && is_array($links[$linkType])) {
                                $linksData = [];
                                foreach ($links[$linkType] as $item) {
                                    $linksData[$item['id']] = [
                                        'position' => $item['position']
                                    ];
                                }
                                //$links[$linkType] = $linksData;
                            }
                        }
                        $brandModel->setProductsData($linksData);
                    }
                }

                $brandModel->save();
                $brandId = $brandModel->getEntityId();
                $this->messageManager->addSuccess(__('The Brand has been saved.'));
                $this->_getSession()->setFormData(false);
                //return $this->_getResultRedirect($resultRedirect, $brandModel->getId());
            } /*catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the brand.'));
            }*/
            catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_getSession()->setFormData($formPostValues);
                $redirectBack = $brandId ? true : 'new';
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->messageManager->addError($e->getMessage());
                $this->_getSession()->setFormData($formPostValues);
                $redirectBack = $brandId ? true : 'new';
            }

            //return $resultRedirect->setPath('*/*/edit', ['id' => $brandId]);
        }else {
            $resultRedirect->setPath('*/*/', ['store' => $storeId]);
            $this->messageManager->addError('No data to save');
            return $resultRedirect;
        }

        if ($redirectBack === 'new') {
            $resultRedirect->setPath(
                '*/*/new'
            );
        } elseif ($redirectBack === 'duplicate' && isset($newProduct)) {
            $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $brandModel->getEntityId(), 'back' => null, '_current' => true]
            );
        } elseif ($redirectBack) {
            $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $brandId, '_current' => true]
            );
        } else {
            $resultRedirect->setPath('*/*/', ['store' => $storeId]);
        }
        return $resultRedirect;
    }

    /**
     * @return StoreManagerInterface
     * @deprecated
     */
    private function getStoreManager()
    {
        if (null === $this->storeManager) {
            $this->storeManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Store\Model\StoreManagerInterface');
        }
        return $this->storeManager;
    }
}

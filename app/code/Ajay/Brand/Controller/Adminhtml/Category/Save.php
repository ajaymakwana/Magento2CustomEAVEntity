<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

use Ajay\Brand\Model\Category;

/**
 * Save Category action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Save extends \Ajay\Brand\Controller\Adminhtml\Category
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
        $categoryId = $productId = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $formPostValues = $this->getRequest()->getPostValue();
       
        if (isset($formPostValues)) {
            $categoryData = $formPostValues['category'];
            $categoryModel = $this->_categoryFactory->create();
            $categoryModel->setStoreId($this->getRequest()->getParam('store', 0));
            $categoryModel->load($categoryId);
            $categoryModel->addData($categoryData);
            
            $categoryModel->setAttributeSetId($categoryModel->getDefaultAttributeSetId());
            //$categoryModel->setEntityTypeId(17);
            try {
                /**
                 * Check "Use Default Value" checkboxes values
                 */
                if (isset($formPostValues['use_default']) && !empty($formPostValues['use_default'])) {
                    foreach ($formPostValues['use_default'] as $attributeCode => $attributeValue) {
                        if ($attributeValue) {
                            $categoryModel->setData($attributeCode, null);
                        }
                    }
                }
               
                /* Prepare relative links */
                /*if (isset($formPostValues['links'])) {
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
                            }
                        }
                        $categoryModel->setProductsData($linksData);
                    }
                }*/

                $categoryModel->save();
                $categoryId = $categoryModel->getEntityId();
                $this->messageManager->addSuccess(__('The category has been saved.'));
                $this->_getSession()->setFormData(false);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_getSession()->setFormData($formPostValues);
                $redirectBack = $categoryId ? true : 'new';
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->messageManager->addError($e->getMessage());
                $this->_getSession()->setFormData($formPostValues);
                $redirectBack = $categoryId ? true : 'new';
            }

            //return $resultRedirect->setPath('*/*/edit', ['id' => $categoryId]);
        }else {
            $this->messageManager->addError('No data to save');
            $resultRedirect->setPath('*/*/', ['store' => $storeId]);
        }

        $hasError = (bool)$this->messageManager->getMessages()->getCountByType(
            \Magento\Framework\Message\MessageInterface::TYPE_ERROR
        );
        if ($this->getRequest()->getPost('return_session_messages_only')) {
            $categoryModel->load($categoryModel->getId());
            // to obtain truncated category name
            /** @var $block \Magento\Framework\View\Element\Messages */
            $block = $this->layoutFactory->create()->getMessagesBlock();
            $block->setMessages($this->messageManager->getMessages(true));

            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData(
                [
                    'messages' => $block->getGroupedHtml(),
                    'error' => $hasError,
                    'category' => $categoryModel->toArray(),
                ]
            );
        }
        if ($redirectBack === 'new') {
            $resultRedirect->setPath(
                '*/*/new'
            );
        } elseif ($redirectBack === 'duplicate' && isset($newProduct)) {
            $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $categoryModel->getEntityId(), 'back' => null, '_current' => true]
            );
        } elseif ($redirectBack) {
            $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $categoryId, '_current' => true]
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

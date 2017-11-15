<?php
/**
 * Customer attribute delete controller
 */

namespace Ajay\Brand\Controller\Adminhtml\Category\Attribute;

use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ajay_Brand::attribute_delete');
    }
    
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('Ajay\Brand\Model\ResourceModel\Eav\Attribute');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The attribute has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a attribute to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
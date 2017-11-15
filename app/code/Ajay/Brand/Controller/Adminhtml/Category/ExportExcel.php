<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Export Excel action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class ExportExcel extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * Dispatch request
     */
    public function execute()
    {
        $fileName = 'brands_category.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()
            ->createBlock('Ajay\Brand\Block\Adminhtml\Category\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}

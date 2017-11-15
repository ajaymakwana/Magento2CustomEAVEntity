<?php

namespace Ajay\Brand\Controller\Adminhtml\Category;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Export Csv action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class ExportCsv extends \Ajay\Brand\Controller\Adminhtml\Category
{
    /**
     * Dispatch request
     */
    public function execute()
    {
        $fileName = 'brands_category.csv';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()
            ->createBlock('Ajay\Brand\Block\Adminhtml\Category\Grid')->getCsv();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}

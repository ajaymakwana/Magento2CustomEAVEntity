<?php

namespace Ajay\Brand\Controller\Adminhtml\Brand;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * ExportXml action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class ExportXml extends \Ajay\Brand\Controller\Adminhtml\Brand
{
    /**
     * Dispatch request
     */
    public function execute()
    {
        $fileName = 'owlcarousel_brands.xml';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()
            ->createBlock('Ajay\Brand\Block\Adminhtml\Brand\Grid')->getXml();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}

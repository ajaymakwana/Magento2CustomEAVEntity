<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Brand\BrandList;

use Magento\Store\Model\ScopeInterface;

/**
 * Abstract brand brand list block
 */
abstract class AbstractList extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_brand;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    /**
     * @var \Ajay\Brand\Model\ResourceModel\Brand\Collection
     */
    protected $_brandCollection;

    /**
     * @var \Ajay\Brand\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory
     * @param \Ajay\Brand\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Ajay\Brand\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
        $this->_brandCollectionFactory = $brandCollectionFactory;
        $this->_url = $url;
    }

    /**
     * Prepare brands collection
     *
     * @return void
     */
    protected function _prepareBrandCollection()
    {
        $this->_brandCollection = $this->_brandCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addActiveFilter()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->setOrder('publish_time', 'DESC');

        if ($this->getPageSize()) {
            $this->_brandCollection->setPageSize($this->getPageSize());
        }
    }

    /**
     * Prepare brands collection
     *
     * @return \Ajay\Brand\Model\ResourceModel\Brand\Collection
     */
    public function getBrandCollection()
    {
        if (is_null($this->_brandCollection)) {
            $this->_prepareBrandCollection();
        }

        return $this->_brandCollection;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        /*if (!$this->_scopeConfig->getValue(
            \Ajay\Brand\Helper\Config::XML_PATH_EXTENSION_ENABLED,
            ScopeInterface::SCOPE_STORE
        )) {
            return '';
        }*/

        return parent::_toHtml();
    }
}

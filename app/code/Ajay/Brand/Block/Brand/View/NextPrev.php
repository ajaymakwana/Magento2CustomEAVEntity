<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Brand\View;

use Magento\Store\Model\ScopeInterface;

/**
 * Brand brand next and prev brand links
 */
class NextPrev extends \Magento\Framework\View\Element\Template
{
    /**
     * Previous brand
     *
     * @var \Ajay\Brand\Model\Brand
     */
    protected $_prevBrand;

    /**
     * Next brand
     *
     * @var \Ajay\Brand\Model\Brand
     */
    protected $_nextBrand;

    /**
     * @var \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    /**
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $_tagCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_brandCollectionFactory = $brandCollectionFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Retrieve true if need to display next-prev links
     *
     * @return boolean
     */
    public function displayLinks()
    {
        return (bool)$this->_scopeConfig->getValue(
            'dnbbrand/brand_view/nextprev/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve prev brand
     * @return \Ajay\Brand\Model\Brand || bool
     */
    public function getPrevBrand()
    {
        if ($this->_prevBrand === null) {
            $this->_prevBrand = false;
            $collection = $this->_getFrontendCollection()->addFieldToFilter(
                'publish_time', [
                    'gteq' => $this->getBrand()->getPublishTime()
                ])
                ->setOrder('publish_time', 'ASC')
                ->setPageSize(1);

            $brand = $collection->getFirstItem();

            if ($brand->getId()) {
                $this->_prevBrand = $brand;
            }
        }

        return $this->_prevBrand;
    }

    /**
     * Retrieve next brand
     * @return \Ajay\Brand\Model\Brand || bool
     */
    public function getNextBrand()
    {
        if ($this->_nextBrand === null) {
            $this->_nextBrand = false;
            $collection = $this->_getFrontendCollection()->addFieldToFilter(
                'publish_time', [
                    'lteq' => $this->getBrand()->getPublishTime()
                ])
                ->setOrder('publish_time', 'DESC')
                ->setPageSize(1);

            $brand = $collection->getFirstItem();

            if ($brand->getId()) {
                $this->_nextBrand = $brand;
            }
        }

        return $this->_nextBrand;
    }

    /**
     * Retrieve brand collection with frontend filters and order
     * @return bool
     */
    protected function _getFrontendCollection()
    {
        $collection = $this->_brandCollectionFactory->create();
        $collection->addActiveFilter()
            ->addFieldToFilter('brand_id', ['neq' => $this->getBrand()->getId()])
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('publish_time', 'DESC')
            ->setPageSize(1);
        return $collection;
    }

    /**
     * Retrieve brand instance
     *
     * @return \Ajay\Brand\Model\Brand
     */
    public function getBrand()
    {
        return $this->_coreRegistry->registry('current_brand_brand');
    }

}

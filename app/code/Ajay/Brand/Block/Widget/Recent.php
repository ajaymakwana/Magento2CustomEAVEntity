<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Widget;

/**
 * Brand recent brands widget
 */
class Recent extends \Ajay\Brand\Block\Brand\BrandList\AbstractList implements \Magento\Widget\Block\BlockInterface
{

    /**
     * @var \Ajay\Brand\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Ajay\Brand\Model\Category
     */
    protected $_category;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory
     * @param \Ajay\Brand\Model\Url $url
     * @param \Ajay\Brand\Model\CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Ajay\Brand\Model\Url $url,
        \Ajay\Brand\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $filterProvider, $brandCollectionFactory, $url, $data);
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * Set brand template
     *
     * @return this
     */
    public function _toHtml()
    {
        $this->setTemplate(
            $this->getData('custom_template') ?: 'widget/recent.phtml'
        );

        return parent::_toHtml();
    }

    /**
     * Retrieve block title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title') ?: __('Recent Brand Brands');
    }

    /**
     * Prepare brands collection
     *
     * @return void
     */
    protected function _prepareBrandCollection()
    {
        $size = $this->getData('number_of_brands');
        if (!$size) {
            $size = (int) $this->_scopeConfig->getValue(
                'dnbbrand/sidebar/recent_brands/brands_per_page',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }

        $this->setPageSize($size);

        parent::_prepareBrandCollection();

        if ($category = $this->getCategory()) {
            $categories = $category->getChildrenIds();
            $categories[] = $category->getId();
            $this->_brandCollection->addCategoryFilter($categories);
        }
    }

    /**
     * Retrieve category instance
     *
     * @return \Ajay\Brand\Model\Category
     */
    public function getCategory()
    {
        if ($this->_category === null) {
            if ($categoryId = $this->getData('category_id')) {
                $category = $this->_categoryFactory->create();
                $category->load($categoryId);

                $storeId = $this->_storeManager->getStore()->getId();
                if ($category->isVisibleOnStore($storeId)) {
                    $category->setStoreId($storeId);
                    return $this->_category = $category;
                }
            }

            $this->_category = false;
        }

        return $this->_category;
    }

    /**
     * Retrieve brand short content
     * @param  \Ajay\Brand\Model\Brand $brand
     *
     * @return string
     */
    public function getShorContent($brand)
    {
        return $brand->getShortFilteredContent();
    }
}

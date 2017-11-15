<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Category;

/**
 * Brand category info
 */
class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Ajay\Brand\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context

     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Ajay\Brand\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Ajay\Brand\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
        $this->_url = $url;
    }

    /**
     * Retrieve category instance
     *
     * @return \Ajay\Brand\Model\Category
     */
    public function getCategory()
    {
        return $this->_coreRegistry->registry('current_brand_category');
    }

    /**
     * Retrieve brand content
     *
     * @return string
     */
    public function getContent()
    {
        $category = $this->getCategory();
        $key = 'filtered_content';
        if (!$category->hasData($key)) {
            $cotent = $this->_filterProvider->getPageFilter()->filter(
                $category->getContent()
            );
            $category->setData($key, $cotent);
        }
        return $category->getData($key);
    }

}

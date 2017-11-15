<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Brand;

use Magento\Store\Model\ScopeInterface;

/**
 * Abstract brand мшуц block
 */
abstract class AbstractBrand extends \Magento\Framework\View\Element\Template
{

    /**
     * Deprecated property. Do not use it.
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Ajay\Brand\Model\Brand
     */
    protected $_brand;

    /**
     * Page factory
     *
     * @var \Ajay\Brand\Model\BrandFactory
     */
    protected $_brandFactory;

    /**
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var string
     */
    protected $_defaultBrandInfoBlock = 'Ajay\Brand\Block\Brand\Info';

    /**
     * @var \Ajay\Brand\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Cms\Model\Page $brand
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Cms\Model\PageFactory $brandFactory
     * @param \Ajay\Brand\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ajay\Brand\Model\Brand $brand,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Ajay\Brand\Model\BrandFactory $brandFactory,
        \Ajay\Brand\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_brand = $brand;
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
        $this->_brandFactory = $brandFactory;
        $this->_url = $url;
    }

    /**
     * Retrieve brand instance
     *
     * @return \Ajay\Brand\Model\Brand
     */
    public function getBrand()
    {
        if (!$this->hasData('brand')) {
            $this->setData('brand',
                $this->_coreRegistry->registry('current_brand_brand')
            );
        }
        return $this->getData('brand');
    }

    /**
     * Retrieve brand short content
     *
     * @return string
     */
    public function getShorContent()
    {
        return $this->getBrand()->getShortFilteredContent();
    }

    /**
     * Retrieve brand content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getBrand()->getFilteredContent();
    }

    /**
     * Retrieve brand info html
     *
     * @return string
     */
    public function getInfoHtml()
    {
        return $this->getInfoBlock()->toHtml();
    }

    /**
     * Retrieve brand info block
     *
     * @return \Ajay\Brand\Block\Brand\Info
     */
    public function getInfoBlock()
    {
        $k = 'info_block';
        if (!$this->hasData($k)) {
            $blockName = $this->getBrandInfoBlockName();
            if ($blockName) {
                $block = $this->getLayout()->getBlock($blockName);
            }

            if (empty($block)) {
                $block = $this->getLayout()->createBlock($this->_defaultBrandInfoBlock, uniqid(microtime()));
            }

            $this->setData($k, $block);
        }

        return $this->getData($k)->setBrand($this->getBrand());
    }

}

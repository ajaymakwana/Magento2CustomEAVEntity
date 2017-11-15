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
 * Brand brand list block
 */
class BrandList extends \Ajay\Brand\Block\Brand\BrandList\AbstractList
{
    /**
     * Block template file
     * @var string
     */
    protected $_defaultToolbarBlock = 'Ajay\Brand\Block\Brand\BrandList\Toolbar';

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $page = $this->_request->getParam(
            \Ajay\Brand\Block\Brand\BrandList\Toolbar::PAGE_PARM_NAME
        );

        if ($page > 1) {
            $this->pageConfig->setRobots('NOINDEX,FOLLOW');
        }

        return parent::_prepareLayout();
    }

    /**
     * Retrieve brand html
     * @param  \Ajay\Brand\Model\Brand $brand
     * @return string
     */
    public function getBrandHtml($brand)
    {
        return $this->getChildBlock('brand.brands.list.item')->setBrand($brand)->toHtml();
    }

    /**
     * Retrieve Toolbar Block
     * @return \Ajay\Brand\Block\Brand\BrandList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();

        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
        return $block;
    }

    /**
     * Retrieve Toolbar Html
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Before block to html
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getBrandCollection();

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);
        $this->setChild('toolbar', $toolbar);

        return parent::_beforeToHtml();
    }

    /**
     * Prepare breadcrumbs
     *
     * @param  string $title
     * @param  string $key
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs($title = null, $key = null)
    {
        if ($breadcrumbsBlock = $this->getBreadcrumbsBlock()) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            $brandTitle = $this->_scopeConfig->getValue(
                'dnbbrand/index_page/title',
                ScopeInterface::SCOPE_STORE
            );
            $breadcrumbsBlock->addCrumb(
                'brand',
                [
                    'label' => __($brandTitle),
                    'title' => __($brandTitle),
                    'link' => $this->_url->getBaseUrl()
                ]
            );

            if ($title) {
                $breadcrumbsBlock->addCrumb($key ?: 'brand_item', ['label' => $title, 'title' => $title]);
            }
        }
    }

    /**
     * Retrieve breadcrumbs block
     *
     * @return mixed
     */
    protected function getBreadcrumbsBlock()
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)) {
            return $this->getLayout()->getBlock('breadcrumbs');
        }

        return false;
    }

}

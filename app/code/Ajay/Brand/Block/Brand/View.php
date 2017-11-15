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
 * Brand brand view
 */
class View extends AbstractBrand
{
    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $brand = $this->getBrand();
        if ($brand) {
            $this->_addBreadcrumbs($brand->getTitle(), 'brand_brand');
            $this->pageConfig->addBodyClass('brand-brand-' . $brand->getIdentifier());
            $this->pageConfig->getTitle()->set($brand->getMetaTitle());
            $this->pageConfig->setKeywords($brand->getMetaKeywords());
            $this->pageConfig->setDescription($brand->getMetaDescription());
            $this->pageConfig->addRemotePageAsset(
                $brand->getBrandUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle(
                    $this->escapeHtml($brand->getTitle())
                );
            }
        }

        return parent::_prepareLayout();
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
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))
        ) {
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
            $breadcrumbsBlock->addCrumb($key, [
                'label' => $title ,
                'title' => $title
            ]);
        }
    }

}

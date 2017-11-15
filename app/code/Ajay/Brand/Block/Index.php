<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block;

use Magento\Store\Model\ScopeInterface;

/**
 * Brand index block
 */
class Index extends \Ajay\Brand\Block\Brand\BrandList
{
    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $this->pageConfig->getTitle()->set($this->_getConfigValue('title'));
        $this->pageConfig->setKeywords($this->_getConfigValue('meta_keywords'));
        $this->pageConfig->setDescription($this->_getConfigValue('meta_description'));
        $this->pageConfig->addRemotePageAsset(
            $this->_url->getBaseUrl(),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );
        return parent::_prepareLayout();
    }

    /**
     * Retrieve brand title
     * @return string
     */
    protected function _getConfigValue($param)
    {
        return $this->_scopeConfig->getValue(
            'dnbbrand/index_page/'.$param,
            ScopeInterface::SCOPE_STORE
        );
    }

}

<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Brand\BrandList\Toolbar;

use Ajay\Brand\Model\Config\Source\LazyLoad;

/**
 * Brand brands list toolbar pager
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{

    /**
     * Retrieve url of all pages
     *
     * @return string
     */
    public function getPagesUrls()
    {
        $urls = [];
        for ($page = $this->getCurrentPage() + 1; $page <= $this->getLastPageNum(); $page++) {
            $urls[$page] = $this->getPageUrl($page);
        }

        return $urls;
    }

    /**
     * Retrieve true olny if can use lazyload
     *
     * @return bool
     */
    public function useLazyload()
    {
        $lastPage = $this->getLastPageNum();
        $currentPage = $this->getCurrentPage();

        return $this->getLazyloadMode()
            && $this->getCollection()->getSize()
            && $lastPage > 1
            && $currentPage < $lastPage;
    }

    /**
     * Retrieve lazyload json config string
     * @param array $config
     *
     * @return string
     */
    public function getLazyloadConfig(array $config = [])
    {
        $config = array_merge([
            'page_url' => $this->getPagesUrls(),
            'current_page' => $this->getCurrentPage(),
            'last_page' => $this->getLastPageNum(),
            'padding' => $this->getLazyloadPadding(),
            'list_wrapper' => $this->getListWrapper(),
            'auto_trigger' => $this->getLazyloadMode() == LazyLoad::ENABLED_WITH_AUTO_TRIGER,
        ], $config);

        return json_encode($config);
    }

    /**
     * Retrieve lazyload mod
     *
     * @return int
     */
    public function getLazyloadMode()
    {
        return (int) $this->_scopeConfig->getValue(
            'dnbbrand/brand_list/lazyload_enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve lazyload padding
     *
     * @return int
     */
    public function getLazyloadPadding()
    {
        return (int) $this->_scopeConfig->getValue(
            'dnbbrand/brand_list/lazyload_padding',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}

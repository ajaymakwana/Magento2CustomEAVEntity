<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Brand\View;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * Brand brand related brands block
 */
class RelatedBrands extends \Ajay\Brand\Block\Brand\BrandList\AbstractList
{
    /**
     * Prepare brands collection
     *
     * @return void
     */
    protected function _prepareBrandCollection()
    {
        $pageSize = (int) $this->_scopeConfig->getValue(
            'dnbbrand/brand_view/related_brands/number_of_brands',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $this->_brandCollection = $this->getBrand()->getRelatedBrands()
            ->addActiveFilter()
            ->setPageSize($pageSize ?: 5);

        $this->_brandCollection->getSelect()->order('rl.position', 'ASC');
    }

    /**
     * Retrieve true if Display Related Brands enabled
     * @return boolean
     */
    public function displayBrands()
    {
        return (bool) $this->_scopeConfig->getValue(
            'dnbbrand/brand_view/related_brands/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve brands instance
     *
     * @return \Ajay\Brand\Model\Category
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
     * Get Block Identities
     * @return Array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Page::CACHE_TAG . '_relatedbrands_'.$this->getBrand()->getId()  ];
    }
}

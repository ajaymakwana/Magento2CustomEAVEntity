<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Catalog\Product;

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
            'dnbbrand/product_page/number_of_related_brands',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$pageSize) {
            $pageSize = 5;
        }
        $this->setPageSize($pageSize);

        parent::_prepareBrandCollection();

        $product = $this->getProduct();
        $this->_brandCollection->getSelect()->joinLeft(
            ['rl' => $product->getResource()->getTable('ajay_brand_brand_relatedproduct')],
            'main_table.brand_id = rl.brand_id',
            ['position']
        )->where(
            'rl.related_id = ?',
            $product->getId()
        );
    }

    /**
     * Retrieve true if Display Related Brands enabled
     * @return boolean
     */
    public function displayBrands()
    {
        return (bool) $this->_scopeConfig->getValue(
            'dnbbrand/product_page/related_brands_enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve brands instance
     *
     * @return \Ajay\Brand\Model\Category
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product',
                $this->_coreRegistry->registry('current_product')
            );
        }
        return $this->getData('product');
    }

    /**
     * Get Block Identities
     * @return Array
     */
    public function getIdentities()
    {
        return [\Magento\Catalog\Model\Product::CACHE_TAG . '_relatedbrands_'.$this->getBrand()->getId()  ];
    }
}

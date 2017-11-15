<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Tag;

use Magento\Store\Model\ScopeInterface;

/**
 * Brand tag brands list
 */
class BrandList extends \Ajay\Brand\Block\Brand\BrandList
{
    /**
     * Prepare brands collection
     *
     * @return void
     */
    protected function _prepareBrandCollection()
    {
        parent::_prepareBrandCollection();
        if ($tag = $this->getTag()) {
            $this->_brandCollection->addTagFilter($tag);
        }
    }

    /**
     * Retrieve tag instance
     *
     * @return \Ajay\Brand\Model\Tag
     */
    public function getTag()
    {
        return $this->_coreRegistry->registry('current_brand_tag');
    }

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        if ($tag = $this->getTag()) {
            $this->_addBreadcrumbs($tag->getTitle(), 'brand_tag');
            $this->pageConfig->addBodyClass('brand-tag-' . $tag->getIdentifier());
            $this->pageConfig->getTitle()->set($tag->getTitle());
            $this->pageConfig->addRemotePageAsset(
                $tag->getTagUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }

        return parent::_prepareLayout();
    }

}

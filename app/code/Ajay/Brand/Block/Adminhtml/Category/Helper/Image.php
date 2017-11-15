<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Image config field renderer
 */
namespace Ajay\Brand\Block\Adminhtml\Category\Helper;

/**
 * Class Image Field
 * @method getFieldConfig()
 * @method setFieldConfig()
 */
class Image extends \Magento\Framework\Data\Form\Element\Image
{
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = $this->_urlBuilder->getBaseUrl(
                    ['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]
                ) . \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . DIRECTORY_SEPARATOR . $this->getValue();
        }
        return $url;
    }
}

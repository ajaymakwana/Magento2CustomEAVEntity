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
 * Brand brand info block
 */
class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * Block template file
     * @var string
     */
    protected $_template = 'brand/info.phtml';

    /**
     * DEPRECATED METHOD!!!!
     * Retrieve formated branded date
     * @var string
     * @return string
     */
    public function getBrandedOn($format = 'Y-m-d H:i:s')
    {
        return $this->getBrand()->getPublishDate($format);
    }

    /**
     * Retrieve 1 if display author information is enabled
     * @return int
     */
    public function authorEnabled()
    {
        return (int) $this->_scopeConfig->getValue(
            'dnbbrand/author/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve 1 if author page is enabled
     * @return int
     */
    public function authorPageEnabled()
    {
        return (int) $this->_scopeConfig->getValue(
            'dnbbrand/author/page_enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}

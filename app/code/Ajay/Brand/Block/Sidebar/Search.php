<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Sidebar;

/**
 * Brand sidebar categories block
 */
class Search extends \Magento\Framework\View\Element\Template
{
	use Widget;

	/**
     * @var \Ajay\Brand\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Ajay\Brand\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ajay\Brand\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_url = $url;
    }

	/**
     * @var string
     */
    protected $_widgetKey = 'search';

	/**
	 * Retrieve query
	 * @return string
	 */
	public function getQuery()
	{
		return urldecode($this->getRequest()->getParam('q', ''));
	}

	/**
	 * Retrieve serch form action url
	 * @return string
	 */
	public function getFormUrl()
	{
		return $this->_url->getUrl('', \Ajay\Brand\Model\Url::CONTROLLER_SEARCH);
	}

}

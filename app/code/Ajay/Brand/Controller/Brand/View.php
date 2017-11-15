<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */
namespace Ajay\Brand\Controller\Brand;

/**
 * Brand Brand view
 */
class View extends \Ajay\Brand\App\Action\Action
{

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    /**
     * View Brand brand action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->moduleEnabled()) {
            return $this->_forwardNoroute();
        }

        $brand = $this->_initBrand();
        if (!$brand) {
            return $this->_forwardNoroute();
        }

        $this->_objectManager->get('\Magento\Framework\Registry')
            ->register('current_brand_brand', $brand);
        $resultPage = $this->_objectManager->get('Ajay\Brand\Helper\Page')
            ->prepareResultPage($this, $brand);
        return $resultPage;
    }

    /**
     * Init Brand
     *
     * @return \Ajay\Brand\Model\Brand || false
     */
    protected function _initBrand()
    {
        $id = $this->getRequest()->getParam('id');
        $storeId = $this->_storeManager->getStore()->getId();

        $brand = $this->_objectManager->create('Ajay\Brand\Model\Brand')->load($id);

        if (!$brand->isVisibleOnStore($storeId)) {
            return false;
        }

        $brand->setStoreId($storeId);

        return $brand;
    }

}

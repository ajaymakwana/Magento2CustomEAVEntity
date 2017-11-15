<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Block\Adminhtml\Brand\Edit\Button;

use Ajay\Brand\Api\Data\BrandInterface;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;


/**
 * Class Generic
 */
class Generic
{
    /**
     * Url Builder
     *
     * @var Context
     */
    protected $context;

    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Generic constructor
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->context = $context;
        $this->registry = $registry;
    }
    /**
     * @param array $args
     * @return string
     */
    public function getSaveUrl(array $args = [])
    {
        $params = ['_current' => false, '_query' => false, 'store' => $this->getStoreId()];
        $params = array_merge($params, $args);
        return $this->getUrl('brand/brand/save', $params);
    }
    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * Return ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->context->getRequest()->getParam('id');
    }


    /**
     * Return Store ID
     *
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->context->getRequest()->getParam('store');
    }

    /**
     * Get product
     *
     * @return ProductInterface
     */
    public function getBrand()
    {
        return $this->registry->registry('current_brand');
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [];
    }
}

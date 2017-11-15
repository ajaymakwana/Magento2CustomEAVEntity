<?php

namespace Ajay\Brand\Model;

use Magento\Store\Model\Store;

class BrandUrlPathGenerator
{
    const XML_PATH_DEIGNIDEA_URL_SUFFIX = 'brand/seo/brand_url_suffix';

    /**
     * Cache for brand rewrite suffix
     *
     * @var array
     */
    protected $brandUrlSuffix = [];

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    /** @var \Ajay\Brand\Api\BrandRepositoryInterface */
    protected $brandRepository;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Ajay\Brand\Api\BrandRepositoryInterface $brandRepository
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Ajay\Brand\Api\BrandRepositoryInterface $brandRepository
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->brandRepository = $brandRepository;
    }

    /**
     * Retrieve Brand Url path
     *
     * @param \Ajay\Brand\Model\Brand $brand
     *
     * @return string
     */
    public function getUrlPath($brand)
    {
        $path = $brand->getData('url_path');
        if ($path === null) {
            $path = $brand->getUrlKey() === false
                ? $this->prepareBrandDefaultUrlKey($brand)
                : $this->prepareBrandUrlKey($brand);
        }
        return $path;
    }

    /**
     * Prepare URL Key with stored brand data (fallback for "Use Default Value" logic)
     *
     * @param \Ajay\Brand\Model\Brand $brand
     * @return string
     */
    protected function prepareBrandDefaultUrlKey(\Ajay\Brand\Model\Brand $brand)
    {
        $storedBrand = $this->brandRepository->get($brand->getId());
        $storedUrlKey = $storedBrand->getUrlKey();
        return $storedUrlKey ?: $brand->formatUrlKey($storedBrand->getName());
    }

    /**
     * Retrieve Brand Url path with suffix
     *
     * @param \Ajay\Brand\Model\Brand $brand
     * @param int $storeId
     * @return string
     */
    public function getUrlPathWithSuffix($brand, $storeId)
    {
        return $this->getUrlPath($brand) . $this->getBrandUrlSuffix($storeId);
    }

    /**
     * Get canonical brand url path
     *
     * @param \Ajay\Brand\Model\Brand $brand
     * @return string
     */
    public function getCanonicalUrlPath($brand)
    {
        $path = 'brand/brand/view/id/' . $brand->getId();
        return $path;
    }

    /**
     * Generate brand url key based on url_key entered by merchant or brand name
     *
     * @param \Ajay\Brand\Model\Brand $brand
     * @return string
     */
    public function getUrlKey($brand)
    {
        return $brand->getUrlKey() === false ? false : $this->prepareBrandUrlKey($brand);
    }

    /**
     * Prepare url key for brand
     *
     * @param \Ajay\Brand\Model\Brand $brand
     * @return string
     */
    protected function prepareBrandUrlKey(\Ajay\Brand\Model\Brand $brand)
    {
        $urlKey = $brand->getUrlKey();
        return $brand->formatUrlKey($urlKey === '' || $urlKey === null ? $brand->getName() : $urlKey);
    }

    /**
     * Retrieve brand rewrite suffix for store
     *
     * @param int $storeId
     * @return string
     */
    protected function getBrandUrlSuffix($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        if (!isset($this->brandUrlSuffix[$storeId])) {
            $this->brandUrlSuffix[$storeId] = $this->scopeConfig->getValue(
                self::XML_PATH_DEIGNIDEA_URL_SUFFIX,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }
        return $this->brandUrlSuffix[$storeId];
    }
}

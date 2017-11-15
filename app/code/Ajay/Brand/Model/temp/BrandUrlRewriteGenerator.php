<?php

namespace Ajay\Brand\Model;

use Ajay\Brand\Model\Brand;
use Ajay\Brand\Model\Brand\CanonicalUrlRewriteGenerator;
use Ajay\Brand\Model\Brand\CurrentUrlRewritesRegenerator;
use Magento\CatalogUrlRewrite\Service\V1\StoreViewService;
use Magento\CatalogUrlRewrite\Model\ObjectRegistryFactory;
use Magento\Store\Model\Store;

class BrandUrlRewriteGenerator
{
    /**
     * Entity type code
     */
    const ENTITY_TYPE = 'ajay_brand';

    /** @var \Magento\CatalogUrlRewrite\Service\V1\StoreViewService */
    protected $storeViewService;

    /** @var \Ajay\Brand\Model\Brand */
    protected $currentbrand;

    /** @var \Ajay\Brand\Model\Brand\CurrentUrlRewritesRegenerator */
    protected $currentUrlRewritesRegenerator;

    /** @var \Ajay\Brand\Model\Brand\CanonicalUrlRewriteGenerator */
    protected $canonicalUrlRewriteGenerator;

    /** @var \Magento\CatalogUrlRewrite\Model\ObjectRegistryFactory */
    protected $objectRegistryFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /**
     * @param \Ajay\Brand\Model\Brand\CanonicalUrlRewriteGenerator $canonicalUrlRewriteGenerator
     * @param \Ajay\Brand\Model\Brand\CurrentUrlRewritesRegenerator $currentUrlRewritesRegenerator
     * @param \Magento\CatalogUrlRewrite\Model\ObjectRegistryFactory $objectRegistryFactory
     * @param \Magento\CatalogUrlRewrite\Service\V1\StoreViewService $storeViewService
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        CanonicalUrlRewriteGenerator $canonicalUrlRewriteGenerator,
        CurrentUrlRewritesRegenerator $currentUrlRewritesRegenerator,
        ObjectRegistryFactory $objectRegistryFactory,
        StoreViewService $storeViewService,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->canonicalUrlRewriteGenerator = $canonicalUrlRewriteGenerator;
        $this->currentUrlRewritesRegenerator = $currentUrlRewritesRegenerator;
        $this->objectRegistryFactory = $objectRegistryFactory;
        $this->storeViewService = $storeViewService;
        $this->storeManager = $storeManager;
    }

    /**
     * Generate brand url rewrites
     *
     * @param \Ajay\Brand\Model\Brand $brand
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
     */
    public function generate(Brand $currentbrand)
    {
        $this->currentbrand = $brand;
        $storeId = $this->currentbrand->getStoreId();

        $urls = $this->isGlobalScope($storeId)
            ? $this->generateForGlobalScope()
            : $this->generateForSpecificStoreView($storeId);

        $this->currentbrand = null;
        return $urls;
    }

    /**
     * Check is global scope
     *
     * @param int|null $storeId
     * @return bool
     */
    protected function isGlobalScope($storeId)
    {
        return null === $storeId || $storeId == Store::DEFAULT_STORE_ID;
    }

    /**
     * Generate list of urls for global scope
     *
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
     */
    protected function generateForGlobalScope()
    {
        $urls = [];
        $brandId = $this->currentbrand->getId();
        foreach ($this->currentbrand->getStoreIds() as $storeId) {
            if (!$this->isGlobalScope($storeId)
                && !$this->storeViewService->doesEntityHaveOverriddenUrlKeyForStore($storeId, $brandId, Brand::ENTITY)
            ) {
                $urls = array_merge($urls, $this->generateForSpecificStoreView($storeId));
            }
        }
        return $urls;
    }

    /**
     * Generate list of urls for specific store view
     *
     * @param int $storeId
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
     */
    protected function generateForSpecificStoreView($storeId)
    {
        /**
         * @var $urls \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
         */
        $urls = array_merge(
            $this->canonicalUrlRewriteGenerator->generate($storeId, $this->currentbrand),
            $this->currentUrlRewritesRegenerator->generate($storeId, $this->currentbrand)
        );

        /* Reduce duplicates. Last wins */
        $result = [];
        foreach ($urls as $url) {
            $result[$url->getTargetPath() . '-' . $url->getStoreId()] = $url;
        }
        return $result;
    }
}

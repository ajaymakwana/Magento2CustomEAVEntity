<?php

namespace Ajay\Brand\Model\Category;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class Source extends AbstractSource implements OptionSourceInterface
{
    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    protected $_configCacheType;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Brand collection factory.
     *
     * @var \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Construct
     *
     * @param \Ajay\Brand\Model\BrandFactory $brandFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     */
    public function __construct(
        \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Cache\Type\Config $configCacheType
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_configCacheType = $configCacheType;
    }

    /**
     * Get list of all available brands
     *
     * @return array
     */
    public function getAllOptions()
    {
        $cacheKey = 'BRAND_CATEGORY_SELECT_STORE_' . $this->_storeManager->getStore()->getCode();
        if ($cache = $this->_configCacheType->load($cacheKey)) {
            $options = unserialize($cache);
        } else {
            //$collection = $this->_categoryFactory->create()->getResourceCollection()->loadByStore();
            $collection = $this->_categoryCollectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->joinAttribute('title','ajay_brand_category/title','entity_id',null,'left',0);

            $options = $collection->toOptionArray();
            $this->_configCacheType->save(serialize($options), $cacheKey);
        }
        return $options;
    }
}
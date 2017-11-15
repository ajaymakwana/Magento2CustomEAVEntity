<?php

namespace Ajay\Brand\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class BrandCategory extends AbstractSource implements OptionSourceInterface
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
     * Brand factory
     *
     * @var \Ajay\Brand\Model\BrandFactory
     */
    protected $_categoryFactory;

    /**
     * Construct
     *
     * @param \Ajay\Brand\Model\BrandFactory $brandFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     */
    public function __construct(
        \Ajay\Brand\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Cache\Type\Config $configCacheType
    ) {
        $this->_categoryFactory = $categoryFactory;
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
        $cacheKey = 'BRANDCATEGORY_SELECT_STORE_' . $this->_storeManager->getStore()->getCode();
        if ($cache = $this->_configCacheType->load($cacheKey)) {
            $options = unserialize($cache);
        } else {
            $collection = $this->_categoryFactory->create()->getResourceCollection();
            $options = $collection->toOptionArray();
            $this->_configCacheType->save(serialize($options), $cacheKey);
        }
        return $options;
    }
}
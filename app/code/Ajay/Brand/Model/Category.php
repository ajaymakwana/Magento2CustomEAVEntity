<?php

namespace Ajay\Brand\Model;

use Ajay\Brand\Api\Data\Category\CategoryInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Model\UrlFinderInterface;

/**
 * Ajay brand
 *
 * @method string getUrlKey()
 * @method Brand setUrlKey(string $urlKey)
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Category extends \Magento\Catalog\Model\AbstractModel implements
    IdentityInterface,
    CategoryInterface
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'ajay_brand_category';

    const CACHE_TAG = 'ajay_brand_category';

    /**
     * Brand Store Id
     */
    const STORE_ID = 'store_id';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ajay_brand_category_entity';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'category';

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * Core data
     *
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filter;

    /** @var UrlFinderInterface */
    protected $urlFinder;

    protected $_productInstance = null;
    /**
     * Id of category tree root
     */
    const TREE_ROOT_ID = 0;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $brandUrl;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\Filter\FilterManager $filter,
     * @param UrlFinderInterface $urlFinder,
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ajay\Brand\Model\Url $brandUrl,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Filter\FilterManager $filter,
        UrlFinderInterface $urlFinder,
        \Ajay\Brand\Model\Brand\Product $productInstance,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,

        array $data = []
    ) {
        $this->_url = $url;
        $this->filter = $filter;
        $this->urlFinder = $urlFinder;
        $this->_productInstance = $productInstance;
        $this->brandUrl = $brandUrl;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Initialize resource mode
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Ajay\Brand\Model\ResourceModel\Category');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getId()];
        return $identities;
    }


    /**
     * Retrieve array of store ids for this brand.
     *
     * @return array
     */
    public function getStoreIds()
    {
        if (!$this->hasStoreIds()) {
            $storeIds = [];
            if ($stores = $this->_storeManager->getStores()) {
                $storeIds = array_keys($stores);
            }
            $this->setStoreIds($storeIds);
        }
        return $this->getData('store_ids');
    }

    /**
     * Format URL key from name or defined key
     *
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        return $this->filter->translitUrl($str);
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * Get product attribute set id
     *
     * @return int
     */
    public function getAttributeSetId()
    {
        return $this->_getData(self::ATTRIBUTE_SET_ID);
    }

    /**
     * Get product name
     *
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getName()
    {
        return $this->_getData(self::NAME);
    }
    //@codeCoverageIgnoreEnd

    /**
     * Get product name
     *
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getDescription()
    {
        return $this->_getData(self::DESCRIPTION);
    }

    /**
     * Get product creation date
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * Get previous product update date
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_getData(self::UPDATED_AT);
    }

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
    /**
     * Set product store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Set product attribute set id
     *
     * @param int $attributeSetId
     * @return $this
     */
    public function setAttributeSetId($attributeSetId)
    {
        return $this->setData(self::ATTRIBUTE_SET_ID, $attributeSetId);
    }

    /**
     * Set product created date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set product updated date
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Ajay\Brand\Model\Brand\Product
     * @author Ajay Makwana
     */
    public function getProductInstance()
    {       
        return $this->_productInstance;
    }

    /**
     * Check product options and type options and save them, too
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function beforeSave()
    {       
        if (!$this->getOrigData('website_ids')) {
            $websiteIds = $this->_getResource()->getWebsiteIds($this);
            $this->setOrigData('website_ids', $websiteIds);
        }
        parent::beforeSave();
    }
    
    /**
     * save product brand relation
     *
     * @access public
     * @return Ajay\Brand\Model\Brand
     * @author Ajay Makwana
     */
    /**
     * Get store ids to which specified item is assigned
     * @access public
     * @param int $producttemplateId
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveProducttemplateRelation($this);
        return parent::_afterSave();
    }

    /**
     * Retrieve brand related products
     * @return \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public function getRelatedProducts()
    {
        return $this->getProductInstance()->getProductCollection($this);
    }

    /**
     * Retrieve collection related product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getRelatedProductCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * Retrieve category url
     * @return string
     */
    public function getCategoryUrl()
    {
        return $this->brandUrl->getUrl($this, URL::CONTROLLER_CATEGORY);
    }

    /**
     * Check if category identifier exist for specific store
     * return category id if category exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId, $websiteId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId, $websiteId);
    }

    /**
     * Retrieve product websites identifiers
     *
     * @return array
     */
    public function getWebsiteIds()
    {
        if (!$this->hasWebsiteIds()) {
            $ids = $this->_getResource()->getWebsiteIds($this);
            $this->setWebsiteIds($ids);
        }
        return $this->getData('website_ids');
    }

    /**
     * Retrieve if is visible on store
     * @return bool
     */
    public function isVisibleOnWebsite($websiteId)
    {
        return $this->getStatus() && array_intersect([0, $websiteId], $this->getWebsiteIds());
    }
}

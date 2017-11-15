<?php

namespace Ajay\Brand\Model\ResourceModel;

use Magento\Catalog\Model\ResourceModel\AbstractResource;

/**
 * Brand entity resource model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Brand extends AbstractResource
{
    /**
     * Product to website linkage table
     *
     * @var string
     */
    protected $_brandWebsiteTable;
    /**
     * Store firstly set attributes to filter selected attributes when used specific store_id
     *
     * @var array
     */
    protected $_attributes   = array();
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Model factory
     *
     * @var \Magento\Catalog\Model\Factory
     */
    protected $_modelFactory;
    /**
     * Brand Product factory.
     *
     * @var \Ajay\Brand\Model\Brand\ProductFactory
     */
    public $_brandProductFactory;

    protected $_productInstance = null;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @param \Magento\Eav\Model\Entity\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Factory $modelFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Eav\Model\Entity\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Factory $modelFactory,
        \Ajay\Brand\Model\Brand\Product $brandProductFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_modelFactory = $modelFactory;
        $this->_brandProductFactory = $brandProductFactory;
        parent::__construct($context, $storeManager, $modelFactory, $data);
        $this->setType(\Ajay\Brand\Model\Brand::ENTITY);
        $this->connectionName  = 'brand';
        $this->setConnection('brand_read', 'brand_write');
        $this->_productCollectionFactory = $productCollectionFactory;
    }
    /**
     * Entity type getter and lazy loader
     *
     * @return \Magento\Eav\Model\Entity\Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Ajay\Brand\Model\Brand::ENTITY);
        }
        return parent::getEntityType();
    }
    /**
     * Process product data before save
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\DataObject $object)
    {
        /**
         * Check if declared category ids in object data.
         */

        if ($object->hasCategoryId()) {
            $object->setCategoryId($object->getCategoryId());
        }

        parent::_beforeSave($object);
    }
    /**
     * Initialize attribute value for object
     *
     * @param Magento\Catalog\Model\AbstractModel $object
     * @param array $valueRow
     * @return Magento\Catalog\Model\ResourceModel\AbstractResource
     */
    /*protected function _setAttributeValue($object, $valueRow)
    {
        $attribute = $this->getAttribute($valueRow['attribute_id']);
        if ($attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $isDefaultStore = $valueRow['store_id'] == $this->getDefaultStoreId();
            if (isset($this->_attributes[$valueRow['attribute_id']])) {
                if ($isDefaultStore) {
                    $object->setAttributeDefaultValue($attributeCode, $valueRow['value']);
                } else {
                    $object->setAttributeDefaultValue(
                        $attributeCode,
                        $this->_attributes[$valueRow['attribute_id']]['value']
                    );
                }
            } else {
                $this->_attributes[$valueRow['attribute_id']] = $valueRow;
            }

            $value   = $valueRow['value'];
            $valueId = $valueRow['value_id'];

            $object->setData($attributeCode, $value);
            if (!$isDefaultStore) {
                $object->setExistsStoreValueFlag($attributeCode);
            }
            $attribute->getBackend()->setEntityValueId($object, $valueId);
        }

        return $this;
    }*/

    /**
     * Reset firstly loaded attributes
     *
     * @param Varien_Object $object
     * @param integer $entityId
     * @param array|null $attributes
     * @return Magento\Catalog\Model\ResourceModel\AbstractResource
     */
    /*public function load($object, $entityId, $attributes = array())
    {
        $this->_attributes = array();
        return parent::load($object, $entityId, $attributes);
    }*/
    /**
     * get product relation model
     *
     * @access public
     * @return Ajay\Brand\Model\Brand\Product
     * @author Ajay Makwana
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = $this->_brandProductFactory;
        }
        return $this->_productInstance;
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
     * @param int $brandId
     * @return array
     * @author Ajay Makwana
     */
    protected function _afterSave(\Magento\Framework\DataObject $object)
    {
        $this->_saveWebsiteIds($object);
        $this->getProductInstance()->saveProductBrandRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * Save Brand website relations
     *
     * @param Ajay\Brand\Model\Brand $brand
     * @return $this
     */
    protected function _saveWebsiteIds($brand)
    {
        if ($this->_storeManager->isSingleStoreMode()) {
            $id = $this->_storeManager->getDefaultStoreView()->getWebsiteId();
            $brand->setWebsiteIds([$id]);
        }
        $websiteIds = $brand->getWebsiteIds();

        $oldWebsiteIds = [];

        $brand->setIsChangedWebsites(false);

        $connection = $this->getConnection();

        $oldWebsiteIds = $this->getWebsiteIds($brand);

        $insert = array_diff($websiteIds, $oldWebsiteIds);
        $delete = array_diff($oldWebsiteIds, $websiteIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $websiteId) {
                $data[] = ['brand_id' => (int)$brand->getEntityId(), 'website_id' => (int)$websiteId];
            }
            $connection->insertMultiple($this->getBrandWebsiteTable(), $data);
        }

        if (!empty($delete)) {
            foreach ($delete as $websiteId) {
                $condition = ['brand_id = ?' => (int)$brand->getEntityId(), 'website_id = ?' => (int)$websiteId];

                $connection->delete($this->getBrandWebsiteTable(), $condition);
            }
        }

        if (!empty($insert) || !empty($delete)) {
            $brand->setIsChangedWebsites(true);
        }

        return $this;
    }

    /**
     * Retrieve brand related products
     * @return \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public function getRelatedProducts()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->getSelect()->joinLeft(
            ['rl' => $this->getTable('ajay_brand_relatedproduct')],
            'e.entity_id = rl.related_id',
            ['position']
        )->where(
            'rl.brand_id = ?',
            $this->getId()
        );
        return $collection;
    }

    /**
     * Retrieve product website identifiers
     *
     * @param \Ajay\Brand\Model\Brand|int $brand
     * @return array
     */
    public function getWebsiteIds($brand)
    {
        $connection = $this->getConnection();

        if ($brand instanceof \Ajay\Brand\Model\Brand) {
            $brandId = $brand->getEntityId();
        } else {
            $brandId = $brand;
        }

        $select = $connection->select()->from(
            $this->getBrandWebsiteTable(),
            'website_id'
        )->where(
            'brand_id = ?',
            (int)$brandId
        );

        return $connection->fetchCol($select);
    }

    /**
     * Product Website table name getter
     *
     * @return string
     */
    public function getBrandWebsiteTable()
    {
        if (!$this->_brandWebsiteTable) {
            $this->_brandWebsiteTable = $this->getTable('ajay_brand_website');
        }
        return $this->_brandWebsiteTable;
    }
}
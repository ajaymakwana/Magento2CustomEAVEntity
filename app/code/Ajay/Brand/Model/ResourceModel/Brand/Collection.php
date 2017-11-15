<?php

namespace Ajay\Brand\Model\ResourceModel\Brand;

/**
 * Brand resource collection
 *
 */
class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection
{
    /**
     * Brand collection factory
     *
     * @var \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     * @param \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        $this->_brandCollectionFactory = $brandCollectionFactory;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $connection
        );
    }

    /**
     * Init collection and determine table names.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ajay\Brand\Model\Brand', 'Ajay\Brand\Model\ResourceModel\Brand');
    }

    /**
     * Convert items array to array for select options.
     *
     * @param $addEmpty bool
     * @return array
     */
    public function toOptionArray($addEmpty = true)
    {
        /** @var \Ajay\Brand\Model\ResourceModel\Brand\Collection $collection */
        $collection = $this->_brandCollectionFactory->create();

        $collection->addAttributeToSelect('name')->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Brand --'), 'value' => ''];
        }
        foreach ($collection as $brand) {
            $options[] = ['label' => $brand->getName(), 'value' => $brand->getId()];
        }

        return $options;
    }

    /**
     * Add is_active filter to collection
     * @return $this
     */
    public function addActiveFilter()
    {
        return $this
            ->addFieldToFilter('status', 1);
    }

    /**
     * Add store filter to collection
     * @param array|int|\Magento\Store\Model\Store  $store
     * @param boolean $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store === null) {
            return $this;
        }

        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof \Magento\Store\Model\Store) {
                $this->_storeId = $store->getId();
                $store = [$store->getId()];
            }

            if (!is_array($store)) {
                $this->_storeId = $store;
                $store = [$store];
            }

            if (in_array(\Magento\Store\Model\Store::DEFAULT_STORE_ID, $store)) {
                return $this;
            }

            if ($withAdmin) {
                $store[] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
            }

            $this->addFilter('store', ['in' => $store], 'public');
        }
        return $this;
    }

    /**
     * Add category filter to collection
     * @param array|int|\Ajay\Brand\Model\Category  $category
     * @return $this
     */
    public function addCategoryFilter($category)
    {
        if (!$this->getFlag('category_filter_added')) {
            if ($category instanceof \Ajay\Brand\Model\Category) {
                $category = [$category->getId()];
            }

            if (!is_array($category)) {
                $category = [$category];
            }

            //$this->addFilter('category_id', ['in' => $category], 'public');
            $this->addAttributeToFilter('category_id', ['in' => $category], 'public');
        }
        return $this;
    }

    /**
     * Add search filter to collection
     * @param string $term
     * @return $this
     */
    public function addSearchFilter($term)
    {
        $collectionFilter[] = ['attribute' => 'title', 'like' => '%' . $term . '%'];
        $this->addAttributeToFilter($collectionFilter);
        /*$this->addFieldToFilter(
            ['attribute' => ['title']],
            [
                ['like' => '%' . $term . '%'],
                ['like' => '%' . $term . '%'],
                ['like' => '% ' . $term . ' %']
            ]
        );*/

        return $this;
    }
}

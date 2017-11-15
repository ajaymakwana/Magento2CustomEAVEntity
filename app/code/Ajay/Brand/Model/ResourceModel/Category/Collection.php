<?php

namespace Ajay\Brand\Model\ResourceModel\Category;

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
    protected $_categoryCollectionFactory;

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
        \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
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
        $this->_init('Ajay\Brand\Model\Category', 'Ajay\Brand\Model\ResourceModel\Category');
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
        $collection = $this->_categoryCollectionFactory->create();

        $collection->addAttributeToSelect('title')->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Category --'), 'value' => ''];
        }
        foreach ($collection as $brand) {
            $options[] = ['label' => $brand->getTitle(), 'value' => $brand->getId()];
        }

        return $options;
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
     * Retrieve gruped category childs
     * @return array
     */
    public function getGroupedChilds()
    {
        $childs = [];
        if (count($this)) {
            foreach($this as $item) {
                $childs[$item->getParentId()][] = $item;
            }
        }
        return $childs;
    }

    /**
     * Retrieve tree ordered categories
     * @return array
     */
    public function getTreeOrderedArray()
    {
        $tree = [];
        if ($childs = $this->getGroupedChilds()) {
            $this->_toTree(0, $childs, $tree);
        }
        return $tree;
    }

    /**
     * Auxiliary function to build tree ordered array
     * @return array
     */
    protected function _toTree($itemId, $childs, &$tree)
    {
        if ($itemId) {
            $tree[] = $this->getItemById($itemId);
        }

        if (isset($childs[$itemId])) {
            foreach($childs[$itemId] as $i) {
                $this->_toTree($i->getId(), $childs, $tree);
            }
        }
    }
}

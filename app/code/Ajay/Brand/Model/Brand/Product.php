<?php
namespace Ajay\Brand\Model\Brand;

class Product extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * brand product collection factory.
     *
     * @var \Ajay\Brand\Model\ResourceModel\Brand\Product\CollectionFactory
     */
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Product\Link\CollectionFactory $linkCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Link\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\CatalogInventory\Helper\Stock $stockHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    protected $_brandProductCollectionFactory;

    protected $_brandProductFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\Link\CollectionFactory $linkCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Link\Product\CollectionFactory $productCollectionFactory,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Ajay\Brand\Model\ResourceModel\Brand\Product\CollectionFactory $brandProductCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,

        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,

        array $data = []
    ) {
        $this->_linkCollectionFactory = $linkCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->stockHelper = $stockHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_brandProductCollectionFactory = $brandProductCollectionFactory;
    }


    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ajay Makwana
     */
    protected function _construct()
    {
        $this->_init('Ajay\Brand\Model\ResourceModel\Brand\Product');
    }

    /**
     * Save data for product Brand-product relation
     * @access public
     * @param  Ajay\Brand\Model\Brand $brand
     * @return Ajay\Brand\Model\Brand\Product
     * @author Ajay Makwana
     */
    public function saveProductBrandRelation($brand)
    {
        $data = $brand->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveProductBrandRelation($brand, $data);
        }
        return $this;
    }

    /**
     * Retrieve linked product collection
     *
     * @return ProductCollection
     */
    public function getProductCollection($brand)
    {
        $collection = $this->getCollection()->addBrandFilter($brand);
        return $collection;
    }
}

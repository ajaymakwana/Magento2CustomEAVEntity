<?php
namespace Ajay\Brand\Model\ResourceModel\Brand;

class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Magento\Framework\Model\ResourceModel\Db\AbstractDb::_construct()
     * @author
     */
    protected function  _construct()
    {
        $this->_init('ajay_brand_relatedproduct', 'related_id');
    }
    /**
     * Save product brand - product relations
     *
     * @access public
     * @param Ajay\Brand\Model\Brand $brand
     * @param array $data
     * @return Ajay\Brand\Model\ResourceModel\Brand
     * @author Ajay Makwana
     */
    public function saveProductBrandRelation($brand, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->getConnection()->quoteInto('brand_id=?', $brand->getId());
        $this->getConnection()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->getConnection()->insert(
                $this->getMainTable(),
                array(
                    'brand_id' => $brand->getId(),
                    'related_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - product brand relations
     *
     * @access public
     * @param Magento\Catalog\Model\Product $prooduct
     * @param array $data
     * @return Ajay\Brand\Model\ResourceModel\Brand\Product
     * @@author Ajay Makwana
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->getConnection()->quoteInto('product_id=?', $product->getId());
        $this->getConnection()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $brandId => $info) {
            $this->getConnection()->insert(
                $this->getMainTable(),
                array(
                    'brand_id' => $brandId,
                    'related_id'    => $product->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}

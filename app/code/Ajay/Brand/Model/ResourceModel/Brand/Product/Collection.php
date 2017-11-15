<?php
namespace Ajay\Brand\Model\ResourceModel\Brand\Product;

class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{

    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;    

    /**
     * join the link table
     *
     * @access public
     * @return Ajay\Brand\Model\ResourceModel\Brand\Product\Collection
     * @author Ajay Makwana
     */
    protected function _joinedFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                ['rl' => $this->getTable('ajay_brand_relatedproduct')],
                'e.entity_id = rl.related_id',
                ['position']
            );


            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add product design filter
     *
     * @access public
     * @param Ajay\Brand\Model\Brand | int $brand
     * @return Ajay\Brand\Model\ResourceModel\Brand\Product\Collection
     * @author Ultimate Module Creator
     */
    public function addBrandFilter($brand)
    {

        if ($brand instanceof \Ajay\Brand\Model\Brand) {
            //$brand = $brand->getId();
        }
        if (!$this->_joinedFields ) {
            $this->_joinedFields();
        }

        $this->getSelect()->where('rl.brand_id = ?', $brand->getId());
        return $this;
    }
}

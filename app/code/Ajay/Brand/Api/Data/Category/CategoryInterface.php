<?php

namespace Ajay\Brand\Api\Data\Category;

/**
 * @api
 */
interface CategoryInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const NAME = 'name';
    
    const DESCRIPTION = 'description';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const ATTRIBUTE_SET_ID = 'attribute_set_id';
    /**#@-*/

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get category name
     *
     * @return string
     */
    public function getName();

    /**
     * Set category name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get category description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set category description
     *
     * @param string $name
     * @return $this
     */
    public function setDescription($description);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Product attribute set id
     *
     * @return int|null
     */
    public function getAttributeSetId();

    /**
     * Set product attribute set id
     *
     * @param int $attributeSetId
     * @return $this
     */
    public function setAttributeSetId($attributeSetId);
}

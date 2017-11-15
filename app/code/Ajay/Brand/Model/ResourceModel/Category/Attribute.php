<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Model\ResourceModel\Category;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Attribute\LockValidatorInterface;

/**
 * Catalog attribute resource model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Attribute extends \Magento\Eav\Model\ResourceModel\Entity\Attribute
{
    /**
     * Eav config
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;
    /**
     * Eav Setup
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $eavSetup;

    /**
     * @var LockValidatorInterface
     */
    protected $attrLockValidator;

    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Eav\Model\ResourceModel\Entity\Type $eavEntityType
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param LockValidatorInterface $lockValidator
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Eav\Model\ResourceModel\Entity\Type $eavEntityType,
        \Magento\Eav\Model\Config $eavConfig,
        LockValidatorInterface $lockValidator,
        \Magento\Eav\Setup\EavSetup $eavSetup,
        $connectionName = null
    ) {
        $this->attrLockValidator = $lockValidator;
        $this->_eavConfig = $eavConfig;
        $this->eavSetup = $eavSetup;
        parent::__construct($context, $storeManager, $eavEntityType, $connectionName);
    }

    /**
     * Perform actions after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $entityType  = $object->getEntityTypeId();
        $setId       = $this->eavSetup->getDefaultAttributeSetId($entityType);
        $groupId     = $this->eavSetup->getDefaultAttributeGroupId($entityType);
        $attributeId = $object->getId();
        $sortOrder   = $object->getPosition();
        $this->eavSetup->addAttributeToGroup($entityType, $setId, $groupId, $attributeId, $sortOrder);

        return parent::_afterSave($object);
    }
}

<?php

namespace Ajay\Brand\Setup;

use Ajay\Brand\Model\Brand\AttributeFactory;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AttributeSetup extends EavSetup
{
    /**
     * Category model factory
     *
     * @var BrandFactory
     */
    private $attributeFactory;

    /**
     * Init
     *
     * @param ModuleDataSetupInterface $setup
     * @param Context $context
     * @param CacheInterface $cache
     * @param CollectionFactory $attrGroupCollectionFactory
     * @param BrandFactory $brandFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory,
        AttributeFactory $attributeFactory
    ) {
        $this->attributeFactory = $attributeFactory;
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }

    /**
     * Default entities and attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefaultEntities()
    {
        return [
            'ajay_brand' => [
                'entity_model' => 'Ajay\Brand\Model\ResourceModel\Brand',
                'attribute_model' => 'Ajay\Brand\Model\ResourceModel\Eav\Attribute',
                'table' => 'ajay_brand_entity',
                'additional_attribute_table'    => 'ajay_brand_eav_attribute',
                'entity_attribute_collection' => 'Ajay\Brand\Model\ResourceModel\Brand\Attribute\Collection',
                'attributes' => [
                    'title' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'frontend_class' => 'validate-length maximum-length-255',
                        'position' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group'          => 'General',
                    ],
                    'category_id' => [
                        'type' => 'int',
                        'label' => 'Categories',
                        'input' => 'text',
                        'required' => true,
                        'position' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group'          => 'General',
                        //'source' => 'Ajay\Brand\Model\Category\Source',
                    ],
                    'image' => [
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => 'Ajay\Brand\Model\Attribute\Backend\Image',
                        'label'          => 'Image',
                        'input'          => 'image',
                        'global'         => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'required'       => false,
                        'position'       => 3,
                    ],
                    'status' => [
                        'type' => 'int',
                        'label' => 'Status',
                        'input' => 'boolean',
                        'required' => true,
                        'position' => 4,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group'          => 'General',
                    ],
                    'position' => [
                        'group'          => 'General',
                        'type'           => 'int',
                        'label'          => 'Position',
                        'input'          => 'text',
                        'global'         => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                        'required'       => false,
                        'position' => 4,
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'position' => 5,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                        'group'          => 'Content',
                    ],
                    'identifier' => [
                        'type' => 'varchar',
                        'label' => 'URL Key',
                        'input' => 'text',
                        'required' => true,
                        'position' => 6,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group'          => 'Search Engine Optimization',
                    ],
                    'meta_title' => [
                        'type' => 'varchar',
                        'label' => 'Meta Title',
                        'input' => 'text',
                        'required' => false,
                        'position' => 7,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group'          => 'Search Engine Optimization',
                    ],
                    'meta_description' => [
                        'type' => 'varchar',
                        'label' => 'Meta Description',
                        'input' => 'textarea',
                        'required' => false,
                        'note' => 'Maximum 255 chars',
                        'class' => 'validate-length maximum-length-255',
                        'position' => 8,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group'          => 'Search Engine Optimization',
                    ],
                ],
            ],
            'ajay_brand_category' => [
                'entity_model' => 'Ajay\Brand\Model\ResourceModel\Category',
                'attribute_model' => 'Ajay\Brand\Model\ResourceModel\Category\Eav\Attribute',
                'table' => 'ajay_brand_category_entity',
                //'entity_attribute_collection' => 'Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection',
                'additional_attribute_table' => 'ajay_brand_eav_attribute',
                'entity_attribute_collection' => 'Ajay\Brand\Model\ResourceModel\Category\Attribute\Collection',
                'attributes' => [
                    'title' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        // 'source' => 'Ajay\Brand\Model\Product\Attribute\Source\Brand',
                        'frontend_class' => 'validate-length maximum-length-255',
                        'position' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'position' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                    ],
                    'status' => [
                        'type' => 'int',
                        'label' => 'Status',
                        'input' => 'boolean',
                        'required' => true,
                        'position' => 3,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                    ],
                    'identifier' => [
                        'type' => 'varchar',
                        'label' => 'URL Key',
                        'input' => 'text',
                        'required' => true,
                        'position' => 3,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'meta_title' => [
                        'type' => 'varchar',
                        'label' => 'Meta Title',
                        'input' => 'text',
                        'required' => false,
                        'position' => 10,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'meta_description' => [
                        'type' => 'varchar',
                        'label' => 'Meta Description',
                        'input' => 'textarea',
                        'required' => false,
                        'note' => 'Maximum 255 chars',
                        'class' => 'validate-length maximum-length-255',
                        'position' => 20,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                ],
            ]
        ];
    }
}

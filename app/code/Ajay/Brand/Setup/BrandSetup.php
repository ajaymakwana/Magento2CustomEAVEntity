<?php

namespace Ajay\Brand\Setup;

use Ajay\Brand\Model\BrandFactory;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class BrandSetup extends EavSetup
{
    /**
     * Category model factory
     *
     * @var BrandFactory
     */
    private $brandFactory;

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
        BrandFactory $brandFactory
    ) {
        $this->brandFactory = $brandFactory;
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }

    /**
     * Creates brand model
     *
     * @param array $data
     * @return \Ajay\Brand\Model\Brand
     * @codeCoverageIgnore
     */
    public function createCategory($data = [])
    {
        return $this->brandFactory->create($data);
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
                //'entity_attribute_collection' => 'Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection',
                'additional_attribute_table' => 'ajay_brand_eav_attribute',
                'entity_attribute_collection' => 'Ajay\Brand\Model\ResourceModel\Brand\Attribute\Collection',
                'attributes' => [
                    'name' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'source' => 'Ajay\Brand\Model\Product\Attribute\Source\Brand',
                        'frontend_class' => 'validate-length maximum-length-255',
                        'sort_order' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                    ],
                    'url_key' => [
                        'type' => 'varchar',
                        'label' => 'URL Key',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 3,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'meta_title' => [
                        'type' => 'varchar',
                        'label' => 'Meta Title',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 10,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'meta_description' => [
                        'type' => 'varchar',
                        'label' => 'Meta Description',
                        'input' => 'textarea',
                        'required' => false,
                        'note' => 'Maximum 255 chars',
                        'class' => 'validate-length maximum-length-255',
                        'sort_order' => 20,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                ],
            ],
            'ajay_brand_category' => [
                'entity_model' => 'Ajay\Brand\Model\ResourceModel\Category',
                'attribute_model' => 'Ajay\Brand\Model\ResourceModel\Eav\Attribute',
                'table' => 'ajay_brand_category_entity',
                //'entity_attribute_collection' => 'Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection',
                'additional_attribute_table' => 'ajay_brand_eav_attribute',
                'entity_attribute_collection' => 'Ajay\Brand\Model\ResourceModel\Category\Attribute\Collection',
                'attributes' => [
                    'name' => [
                        'type' => 'varchar',
                        'label' => 'Name',
                        'input' => 'text',
                        'source' => 'Ajay\Brand\Model\Product\Attribute\Source\Brand',
                        'frontend_class' => 'validate-length maximum-length-255',
                        'sort_order' => 1,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'description' => [
                        'type' => 'text',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 2,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'wysiwyg_enabled' => true,
                        'is_html_allowed_on_front' => true,
                    ],
                    'url_key' => [
                        'type' => 'varchar',
                        'label' => 'URL Key',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 3,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                    ],
                    'meta_title' => [
                        'type' => 'varchar',
                        'label' => 'Meta Title',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 10,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'meta_description' => [
                        'type' => 'varchar',
                        'label' => 'Meta Description',
                        'input' => 'textarea',
                        'required' => false,
                        'note' => 'Maximum 255 chars',
                        'class' => 'validate-length maximum-length-255',
                        'sort_order' => 20,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    ],
                ],
            ],
        ];
    }
}

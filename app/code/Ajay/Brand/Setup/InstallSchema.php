<?php

namespace Ajay\Brand\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        /**
         * Create table 'ajay_brand_eav_attribute'
         */
        $table = $installer->getConnection()
            ->newTable(
                $installer->getTable('ajay_brand_eav_attribute')
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Attribute ID'
            )
            ->addColumn(
                'frontend_input_renderer',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Frontend Input Renderer'
            )
            ->addColumn(
                'is_global',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Is Global'
            )
            ->addColumn(
                'is_visible',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Is Visible'
            )
            ->addColumn(
                'is_searchable',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Searchable'
            )
            ->addColumn(
                'is_filterable',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Filterable'
            )
            ->addColumn(
                'is_comparable',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Comparable'
            )
            ->addColumn(
                'is_visible_on_front',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Visible On Front'
            )
            ->addColumn(
                'is_html_allowed_on_front',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is HTML Allowed On Front'
            )
            ->addColumn(
                'is_used_for_price_rules',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Used For Price Rules'
            )
            ->addColumn(
                'is_filterable_in_search',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Filterable In Search'
            )
            ->addColumn(
                'used_in_product_listing',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Used In Product Listing'
            )
            ->addColumn(
                'used_for_sort_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Used For Sorting'
            )
            ->addColumn(
                'apply_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Apply To'
            )
            ->addColumn(
                'is_visible_in_advanced_search',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Visible In Advanced Search'
            )
            ->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Position'
            )
            ->addColumn(
                'is_wysiwyg_enabled',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is WYSIWYG Enabled'
            )
            ->addColumn(
                'is_used_for_promo_rules',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Used For Promo Rules'
            )
            ->addColumn(
                'is_required_in_admin_store',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Required In Admin Store'
            )
            ->addColumn(
                'is_used_in_grid',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Used in Grid'
            )
            ->addColumn(
                'is_visible_in_grid',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Visible in Grid'
            )
            ->addColumn(
                'is_filterable_in_grid',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Filterable in Grid'
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_eav_attribute', ['used_for_sort_by']),
                ['used_for_sort_by']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_eav_attribute', ['used_in_product_listing']),
                ['used_in_product_listing']
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_eav_attribute', 'attribute_id', 'eav_attribute', 'attribute_id'),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Catalog EAV Attribute Table'
            );
        $installer->getConnection()
            ->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_entity'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'attribute_set_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute Set ID'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity', ['attribute_set_id']),
                ['attribute_set_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity',
                    'attribute_set_id',
                    'eav_attribute_set',
                    'attribute_set_id'
                ),
                'attribute_set_id',
                $installer->getTable('eav_attribute_set'),
                'attribute_set_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Brand Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_entity_datetime'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_entity_datetime'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_entity_datetime',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_datetime', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_datetime', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_datetime',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_datetime',
                    'entity_id',
                    'ajay_brand_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_datetime', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Datetime Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_entity_decimal'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_entity_decimal'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_entity_decimal',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_decimal', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_decimal', ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_decimal',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_decimal',
                    'entity_id',
                    'ajay_brand_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_decimal', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Decimal Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_entity_int'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_entity_int'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_entity_int',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_int', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_int', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_int', 'attribute_id', 'eav_attribute', 'attribute_id'),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_int', 'entity_id', 'ajay_brand_entity', 'entity_id'),
                'entity_id',
                $installer->getTable('ajay_brand_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_int', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Integer Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_entity_text'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_entity_text'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_entity_text',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_text', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_text', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_text',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_text',
                    'entity_id',
                    'ajay_brand_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_text', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Text Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_entity_varchar'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_entity_varchar'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_entity_varchar',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_varchar', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_entity_varchar', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_varchar',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_entity_varchar',
                    'entity_id',
                    'ajay_brand_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_entity_varchar', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Varchar Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_relatedproduct'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ajay_brand_relatedproduct')
        )->addColumn(
            'brand_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'unsigned' => true, 'primary' => true],
            'Brand ID'
        )->addColumn(
            'related_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Related Product ID'
        )
        ->addColumn(
            'position',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Position'
        )->addIndex(
            $installer->getIdxName('ajay_brand_relatedproduct', ['related_id']),
            ['related_id']
        )->addForeignKey(
            $installer->getFkName('ajay_brand_relatedproduct', 'brand_id', 'ajay_brand_entity', 'entity_id'),
            'brand_id',
            $installer->getTable('ajay_brand_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('ajay_brand_relatedproduct', 'related_id', 'catalog_product_entity', 'entity_id'),
            'related_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Brand To Product Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_entity'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_category_entity'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'attribute_set_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute Set ID'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity', ['attribute_set_id']),
                ['attribute_set_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity',
                    'attribute_set_id',
                    'eav_attribute_set',
                    'attribute_set_id'
                ),
                'attribute_set_id',
                $installer->getTable('eav_attribute_set'),
                'attribute_set_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Brand Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_entity_datetime'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_category_entity_datetime'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_category_entity_datetime',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_datetime', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_datetime', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_datetime',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_datetime',
                    'entity_id',
                    'ajay_brand_category_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_datetime', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Datetime Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_entity_decimal'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_category_entity_decimal'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_category_entity_decimal',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_decimal', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_decimal', ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_decimal',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_decimal',
                    'entity_id',
                    'ajay_brand_category_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_decimal', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Decimal Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_entity_int'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_category_entity_int'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_category_entity_int',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_int', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_int', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_int', 'attribute_id', 'eav_attribute', 'attribute_id'),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_int', 'entity_id', 'ajay_brand_category_entity', 'entity_id'),
                'entity_id',
                $installer->getTable('ajay_brand_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_int', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Integer Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_entity_text'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_category_entity_text'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_category_entity_text',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_text', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_text', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_text',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_text',
                    'entity_id',
                    'ajay_brand_category_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_text', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Text Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_entity_varchar'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ajay_brand_category_entity_varchar'))
            ->addColumn(
                'value_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Attribute ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Entity ID'
            )
            ->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Value'
            )
            ->addIndex(
                $installer->getIdxName(
                    'ajay_brand_category_entity_varchar',
                    ['entity_id', 'attribute_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['entity_id', 'attribute_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_varchar', ['attribute_id']),
                ['attribute_id']
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_entity_varchar', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_varchar',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'ajay_brand_category_entity_varchar',
                    'entity_id',
                    'ajay_brand_category_entity',
                    'entity_id'
                ),
                'entity_id',
                $installer->getTable('ajay_brand_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_entity_varchar', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Catalog Product Varchar Attribute Backend Table');
        $installer->getConnection()->createTable($table);


        /**
         * Create table 'ajay_brand_website'
         */
        $table = $installer->getConnection()
            ->newTable(
                $installer->getTable('ajay_brand_website')
            )
            ->addColumn(
                'brand_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Brand ID'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Website ID'
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_website', ['website_id']),
                ['website_id']
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_website', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_website', 'brand_id', 'ajay_brand_entity', 'entity_id'),
                'brand_id',
                $installer->getTable('ajay_brand_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Brand To Website Linkage Table'
            );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ajay_brand_category_website'
         */
        $table = $installer->getConnection()
            ->newTable(
                $installer->getTable('ajay_brand_category_website')
            )
            ->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Brand ID'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Website ID'
            )
            ->addIndex(
                $installer->getIdxName('ajay_brand_category_website', ['website_id']),
                ['website_id']
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_website', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('ajay_brand_category_website', 'category_id', 'ajay_brand_category_entit', 'entity_id'),
                'category_id',
                $installer->getTable('ajay_brand_category_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Brand To Website Linkage Table'
            );
        $installer->getConnection()->createTable($table);
    }
}
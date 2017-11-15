<?php

namespace Ajay\Brand\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Attribute setup factory
     *
     * @var AttributeSetupFactory
     */
    private $attributeSetupFactory;

    /**
     * Init
     *
     * @param AttributeSetupFactory $attributeSetupFactory
     */
    public function __construct(AttributeSetupFactory $attributeSetupFactory)
    {
        $this->attributeSetupFactory = $attributeSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Ajay\Brand\Setup\AttributeSetup $AttributeSetup */
        $attributeSetup = $this->attributeSetupFactory->create(['setup' => $setup]);

        $attributeSetup->installEntities();
    }
}

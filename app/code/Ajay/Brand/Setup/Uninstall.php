<?php
/**
 * Created by PhpStorm.
 * User: Ajay
 * Date: 31-05-2017
 * Time: 17:13
 */
namespace Ajay\Brand\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        

        $installer->endSetup();
    }
}
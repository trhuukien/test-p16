<?php
namespace Bss\CustomToolTipCO\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Bss\CustomToolTipCO\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    const OPTION_TYPE_VALUE_TABLE = "catalog_product_option_type_value";

    /**
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $installer->getConnection()->addColumn(
                $installer->getTable(self::OPTION_TYPE_VALUE_TABLE),
                'bss_tooltip_content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Bss Option Type Value ToolTip Content'
                ]
            );

            if (!$installer->tableExists('bss_tooltip_product_attribute')) {
                $table = $installer->getConnection()
                    ->newTable(
                        $installer->getTable('bss_tooltip_product_attribute')
                    )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'ID'
                    )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Product ID'
                    )
                    ->addColumn(
                        'product_attribute_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Attribute ID'
                    )
                    ->addColumn(
                        'content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '2M',
                        [],
                        'Tooltip Content'
                    )
                    ->addForeignKey(
                        $installer->getFkName('bss_tooltip_product_attribute', 'product_id', 'catalog_product_entity', 'entity_id'),
                        'product_id',
                        $installer->getTable('catalog_product_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName('bss_tooltip_product_attribute', 'product_attribute_id', 'eav_attribute', 'attribute_id'),
                        'product_attribute_id',
                        $installer->getTable('eav_attribute'),
                        'attribute_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    );
                $installer->getConnection()->createTable($table);
            }
        }

        $installer->getConnection()->changeColumn(
                $installer->getTable(self::OPTION_TYPE_VALUE_TABLE),
                'bss_tooltip_content',
                'bss_tooltip_content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '2M',
                    'nullable' => true,
                    'comment' => 'Bss Option Type Value ToolTip Content'
                ]
            );

        $installer->endSetup();
    }
}

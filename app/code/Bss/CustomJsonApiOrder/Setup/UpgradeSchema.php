<?php
namespace Bss\CustomJsonApiOrder\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $connection = $setup->getConnection();
        $connection->addColumn($setup->getTable('quote_item'), 'custom_option_price', [
            'type'     => Table::TYPE_DECIMAL,
            'length' => '20,4',
            'comment'  => 'Total custom option price'
        ]);

        $connection->addColumn($setup->getTable('sales_order_item'), 'custom_option_price', [
            'type'     => Table::TYPE_DECIMAL,
            'length' => '20,4',
            'comment'  => 'Total custom option price'
        ]);
        
        
        $setup->endSetup();
    }
}

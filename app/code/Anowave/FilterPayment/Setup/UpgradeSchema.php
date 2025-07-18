<?php
/**
 * Anowave Magento 2 Filter Payment Method
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * http://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2020 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */
namespace Anowave\FilterPayment\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$setup->startSetup();
		
		if (version_compare($context->getVersion(), '2.0.8', '<')) 
		{
		    $sql = [];
		    
		    $sql[] = "SET foreign_key_checks = 0";
		    
		    $sql[] = "CREATE TABLE  IF NOT EXISTS " . $setup->getTable('ae_filterpayment_group') . " (entity_id int(6) NOT NULL AUTO_INCREMENT,entity_group_id int(6) DEFAULT NULL,entity_group_payment varchar(255) DEFAULT NULL,PRIMARY KEY (entity_id),KEY entity_group_id (entity_group_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		    
		    $sql[] = "SET foreign_key_checks = 1";
		    
		    foreach ($sql as $query)
		    {
		        $setup->run($query);
		    }      
		        
		}  
		
		if (version_compare($context->getVersion(), '3.0.0', '<'))
		{
		    $sql = [];
		    
		    $sql[] = "SET foreign_key_checks = 0";
		    
		    $sql[] = "CREATE TABLE  IF NOT EXISTS " . $setup->getTable('ae_filtershipping_group') . " (entity_id int(6) NOT NULL AUTO_INCREMENT,entity_group_id int(6) DEFAULT NULL,entity_group_shipping varchar(255) DEFAULT NULL,PRIMARY KEY (entity_id),KEY entity_group_id (entity_group_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		    
		    $sql[] = "SET foreign_key_checks = 1";
		    
		    foreach ($sql as $query)
		    {
		        $setup->run($query);
		    }
		    
		}  
		
		$setup->endSetup();
	}
}
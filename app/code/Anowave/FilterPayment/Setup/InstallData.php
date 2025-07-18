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

use Anowave\FilterPayment\Model\Entity\Attribute\Source\PaymentMethods;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
	/**
	 * Customer attribute code 
	 * 
	 * @var string
	 */
	const ATTRIBUTE = 'allowed_payment_methods';

    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * Eav setup factory
     *
     * @var EavSetup
     */
    protected $eavSetupFactory;

    /**
     * Init
     *
     * @param CustomerSetupFactory $customerSetupFactory
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct
    (
        CustomerSetupFactory $customerSetupFactory,
        EavSetupFactory $eavSetupFactory
    )
    {
    	/**
    	 * Customer factory 
    	 * 
    	 * @var \Anowave\FilterPayment\Setup\InstallData $customerSetupFactory
    	 */
        $this->customerSetupFactory = $customerSetupFactory;
        
        /**
         * EAV setup factory 
         * 
         * @var \Anowave\FilterPayment\Setup\InstallData $eavSetupFactory
         */
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $customerSetup->addAttribute(Customer::ENTITY, static::ATTRIBUTE, 
        [
            'type' 			=> 'text',
            'label' 		=> 'Allowed Payment Methods',
            'input' 		=> 'multiselect',
            'source' 		=> PaymentMethods::class,
            'backend' 		=> ArrayBackend::class,
            'system' 		=> false,
            'required' 		=> false,
            'position' 		=> 800
        ]);

        $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, static::ATTRIBUTE)->setData('used_in_forms', ['adminhtml_customer'])->save();

        $eavSetup->addAttribute(Product::ENTITY, static::ATTRIBUTE, 
        [
            'type' 			=> 'text',
            'label' 		=> 'Allowed Payment Methods',
            'input' 		=> 'multiselect',
            'source' 		=> PaymentMethods::class,
            'backend' 		=> ArrayBackend::class,
            'required' 		=> false,
            'global' 		=> Attribute::SCOPE_STORE,
            'user_defined' 	=> false,
            'apply_to' 		=> ''
        ]);

        $setup->endSetup();
    }
}
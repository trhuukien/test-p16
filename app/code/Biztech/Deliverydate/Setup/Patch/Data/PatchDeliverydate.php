<?php
namespace Biztech\Deliverydate\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class PatchDeliverydate implements DataPatchInterface,PatchRevertableInterface
{
   /** @var ModuleDataSetupInterface */
   private $moduleDataSetup;

   /** @var EavSetupFactory */
   private $eavSetupFactory;

   /**
    * @param ModuleDataSetupInterface $moduleDataSetup
    * @param EavSetupFactory $eavSetupFactory
    */
   public function __construct(
       ModuleDataSetupInterface $moduleDataSetup,
       EavSetupFactory $eavSetupFactory
   ) {
       $this->moduleDataSetup = $moduleDataSetup;
       $this->eavSetupFactory = $eavSetupFactory;
    }

   /**
    * {@inheritdoc}
    */
   public function apply()
   {
       /** @var EavSetup $eavSetup */ 
       $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cut_off_time_hours',[
                'group' => 'Delivery Date Options',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Cut Off Hour',
                'input' => 'select',
                'class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'visible_in_advanced_search' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'used_for_promo_rules' => false,
                'unique' => false,
                'unique' => false,
                'source' => 'Biztech\Deliverydate\Model\Config\Cutoffhour',
                'apply_to' => 'simple,grouped,configurable,bundle',
                    ]
        );

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cut_off_time_minutes',[
                'group' => 'Delivery Date Options',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Cut Off Minutes',
                'input' => 'select',
                'class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'visible_in_advanced_search' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'used_for_promo_rules' => false,
                'unique' => false,
                'unique' => false,
                'source' => 'Biztech\Deliverydate\Model\Config\CutoffMinuteSecond',
                'apply_to' => 'simple,grouped,configurable,bundle',
                    ]
        );   

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'cut_off_time_seconds',[
                'group' => 'Delivery Date Options',
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Cut Off Seconds',
                'input' => 'select',
                'class' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'visible_in_advanced_search' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'used_for_promo_rules' => false,
                'unique' => false,
                'unique' => false,
                'source' => 'Biztech\Deliverydate\Model\Config\CutoffMinuteSecond',
                'apply_to' => 'simple,grouped,configurable,bundle',
                    ]
        );           

        $insSetup = $this->eavSetupFactory->create()->getSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $insSetup]);
        
        $this->moduleDataSetup->getConnection()->endSetup();
        return [];
   }

   /**
    * {@inheritdoc}
    */
   public static function getDependencies()
   {
       return [];
   }

   /**
     * @inheritdoc
     */
    public function revert()
    {
        return [];
    }

   /**
    * {@inheritdoc}
    */
   public function getAliases()
   {
       return [];
   }

   /**
   * {@inheritdoc}
   */
   public static function getVersion()
   {
      return '1.2.4';
   }
}
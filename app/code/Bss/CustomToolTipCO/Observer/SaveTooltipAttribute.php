<?php
namespace Bss\CustomToolTipCO\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Bss\CustomToolTipCO\Model\ToolTipAttributeFactory;

class SaveTooltipAttribute implements ObserverInterface
{

    /**
     * @var Bss\CustomToolTipCO\Model\ToolTipAttributeFactory
     */
    protected $toolTipAttributeFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $message;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    protected $connection;

    /**
     * @param Bss\CustomToolTipCO\Model\ToolTipAttributeFactory $toolTipAttributeFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\Message\ManagerInterface $message
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        ToolTipAttributeFactory $toolTipAttributeFactory,
        \Magento\Framework\Message\ManagerInterface $message
    ) {
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
        $this->toolTipAttributeFactory = $toolTipAttributeFactory;
        $this->message = $message;
    }

    /**
     * Save Data Attribute Tooltip Content
     *
     * @param Observer $observer
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        if ($product->getTypeId() == 'configurable' && $product->getId()!=null) {
            $model = $this->toolTipAttributeFactory->create();
            $productId = $product->getId();
            $listAttributeTooltip = $product->getAttributeTooltip();
            if (!empty($listAttributeTooltip)) {
                $attrs = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
                $collection = $model->getCollection();
                $items = $collection->addFieldToFilter('product_id',['in' => $productId]);
                if (empty($items->getData())) {
                    foreach ($listAttributeTooltip as $data) {
                        foreach ($attrs as $attr) {
                            if(in_array($data['product_attribute_value'],$attr)){
                                $productAttributeId = $attr['attribute_id'];
                            }
                        }
                        $dataInsert[] = [
                            'product_id' => $productId,
                            'product_attribute_id' => $productAttributeId,
                            'content' => $data['content']
                        ];
                    }
                    $this->insertMultiple('bss_tooltip_product_attribute',$dataInsert);
                } else {
                    foreach ($items as $item) {
                        foreach ($listAttributeTooltip as $data) {
                            foreach ($attrs as $attr) {
                                if(in_array($data['product_attribute_value'],$attr)){
                                    if($attr['attribute_id'] == $item->getproductAttributeId()){
                                        $dataUpdate[] = [
                                            'id' => $item->getid(),
                                            'content' => $data['content']
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    $this->insertOnDuplicate('bss_tooltip_product_attribute',$dataUpdate);
                }
            }
        }
    }

    /**
     * Insert Multiple Table
     *
     * @param array $table
     * @param array $data
     * @return int|void
     */
    public function insertMultiple($table, $data)
    {
        try {
            $tableName = $this->resource->getTableName($table);
            return $this->connection->insertMultiple($tableName, $data);
        } catch (\Exception $e) {
            $this->message->addErrorMessage($e, __($e->getMessage()));
        }
    }

    /**
     * Update Multiple Table
     *
     * @param array $table
     * @param array $data
     * @return int|void
     */
    public function insertOnDuplicate($table, $data)
    {
        try {
            $tableName = $this->resource->getTableName($table);
            return $this->connection->insertOnDuplicate($tableName, $data);
        } catch (\Exception $e) {
            $this->message->addErrorMessage($e, __($e->getMessage()));
        }
    }

}

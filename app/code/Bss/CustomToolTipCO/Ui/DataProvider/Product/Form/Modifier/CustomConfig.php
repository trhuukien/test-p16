<?php
namespace Bss\CustomToolTipCO\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Textarea;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Bss\CustomToolTipCO\Model\ResourceModel\ToolTipAttribute\CollectionFactory;

class CustomConfig extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * @param LocatorInterface $locator
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        LocatorInterface $locator,
        CollectionFactory $collectionFactory
    ) {
        $this->locator = $locator;
        $this->collection = $collectionFactory->create();
    }

    /**
     * modifyData
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();
        $productId = $product->getId();
        $items = $this->collection->addFieldToFilter('product_id',['in' => $productId]);
        if ($product->getTypeId() == 'configurable' && $productId!=null) {
            $attrs = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
            if (empty($items->getData())) {
                foreach ($attrs as $attr) {
                    $dataToolTipAttribute[] = [
                        'product_attribute_value' => $attr['attribute_code'],
                        'content' => '',
                    ];
                }
            } else {
                foreach ($attrs as $attr) {
                    foreach ($items as $item) {
                        $dataTooltip = $item->getData();
                        if (in_array($attr['attribute_id'], $dataTooltip)) {
                            $dataToolTipAttribute[] = [
                                'product_attribute_value' => $attr['attribute_code'],
                                'content' => $dataTooltip['content'],
                            ];
                        }
                    }
                }
            }
            $data[$productId]['product']['attribute_tooltip'] = $dataToolTipAttribute;
            return $data;
        }
        return $data;
    }

    /**
     * modifyMeta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $product = $this->locator->getProduct();
        if ($product->getTypeId() == 'configurable') {
            $meta = array_replace_recursive(
                $meta,
                [
                    'custom_fieldset' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Attribute Tooltip Content'),
                                    'componentType' => Fieldset::NAME,
                                    'dataScope' => 'data.product',
                                    'collapsible' => true,
                                    'sortOrder' => 5,
                                ],
                            ],
                        ],
                        'children' => [
                            "attribute_tooltip" => $this->getGridConfig(10)
                        ],
                    ]
                ]
            );
            return $meta;
        }
        return $meta;
    }

    /**
     * Get Dynamic Row Config Children
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getGridConfig($sortOrder) {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'additionalClasses' => 'admin__field-wide',
                        'renderDefaultRecord' => true,
                        'sortOrder' => $sortOrder,
                        'addButton' => false,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        'product_attribute_value' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label' => __('Attribute'),
                                        'componentType' => Field::NAME,
                                        'formElement' => Input::NAME,
                                        'dataType' => Text::NAME,
                                        'dataScope' => 'product_attribute_value',
                                        'visible' => true,
                                        'disabled' => true,
                                    ],
                                ],
                            ],
                        ],
                        'content' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label' => __('Tooltip'),
                                        'componentType' => Field::NAME,
                                        'formElement' => Textarea::NAME,
                                        'dataType' => Text::NAME,
                                        'dataScope' => 'content',
                                        'visible' => true,
                                        'disabled' => false,
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ];
    }
}

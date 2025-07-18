<?php

namespace Biztech\Deliverydate\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;

/**
 * Data provider for "Custom Attribute" field of product page
 */
class Datetime extends AbstractModifier {

    /**
     * @param ArrayManager                $arrayManager
     */
    public function __construct(
    ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta) {
        $meta = $this->enableTime($meta);

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data) {
        return $data;
    }

    /**
     * Customise Custom Attribute field
     *
     * @param array $meta
     *
     * @return array
     */
    protected function enableTime(array $meta) {
        $fieldCode = 'cut_off_time';

        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');

        if (!$elementPath) {
            return $meta;
        }

        $meta = $this->arrayManager->merge(
                $containerPath, $meta, [
            'children' => [
                $fieldCode => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'default' => '',
                                'formElement' => 'date',
                                'options' => [
                                    'controlType' => 'select',
                                    'dateFormat' => 'yyyy-MM-dd',
//                                    'showsDate' => false,
                                    'timeFormat' => 'HH:mm:ss',
                                    'showsTime' => true,
                                    'timeOnly' => true,
                                    'showButtonPanel' => false,
                                ]
                            ],
                        ],
                    ],
                ]
            ]
                ]
        );


        return $meta;
    }

    /*   protected function enableTime(array $meta) {
      $fieldCode = 'cut_off_time';

      $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
      $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');

      if (!$elementPath) {
      return $meta;
      }

      $meta = $this->arrayManager->merge(
      $containerPath, $meta, [
      'children' => [
      $fieldCode => [
      'arguments' => [
      'data' => [
      'config' => [
      'componentType' => 'dynamicRows',
      //                                'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
      'renderDefaultRecord' => false,
      'recordTemplate' => 'record',
      'dataScope' => '',
      'dndConfig' => [
      'enabled' => false,
      ],
      'disabled' => $this->arrayManager->get($elementPath . '/arguments/data/config/disabled', $meta),
      'sortOrder' => $this->arrayManager->get($elementPath . '/arguments/data/config/sortOrder', $meta),
      ],
      ],
      ],
      'children' => [
      'record' => [
      'arguments' => [
      'data' => [
      'config' => [
      'componentType' => Container::NAME,
      'isTemplate' => true,
      'is_collection' => true,
      'component' => 'Magento_Ui/js/dynamic-rows/record',
      'dataScope' => '',
      ],
      ],
      ],
      'children' => [
      'hour' => [
      'arguments' => [
      'data' => [
      'config' => [
      'formElement' => Select::NAME,
      'componentType' => Field::NAME,
      'dataType' => Text::NAME,
      'dataScope' => 'hour',
      'label' => __('Hour'),
      'options' => $this->getHour(),
      'value' => $this->getHour(),
      'sortOrder' => 20,
      //'template' => 'ui/form/components/complex',
      //                                                'component' => 'Magento_Ui/js/form/element/select',
      ],
      ],
      ],
      ],
      /* 'minute' => [
      'arguments' => [
      'data' => [
      'config' => [
      'formElement' => Select::NAME,
      'componentType' => Field::NAME,
      'dataType' => Text::NAME,
      'dataScope' => 'minute',
      'label' => __('Minute'),
      'options' => $this->getMinuteSecond(),
      ],
      ],
      ],
      ],
      'second' => [
      'arguments' => [
      'data' => [
      'config' => [
      'formElement' => Select::NAME,
      'componentType' => Field::NAME,
      'dataType' => Text::NAME,
      'dataScope' => 'second',
      'label' => __('Second'),
      'options' => $this->getMinuteSecond(),
      ],
      ],
      ],
      ] * /
      ],
      ],
      ],
      ]
      ]
      ]
      );

      $meta = $this->arrayManager->set(
      $this->arrayManager->slicePath($elementPath, 0, -3)
      . '/' . $fieldCode, $meta, $this->arrayManager->get($elementPath, $meta)
      );
      $meta = $this->arrayManager->remove(
      $this->arrayManager->slicePath($elementPath, 0, -2), $meta
      );

      return $meta;
      }

      protected function getHour() {
      $hour = [];
      for ($i = 0; $i <= 23; $i++) {
      $hour[] = [
      'label' => $i,
      'value' => $i,
      ];
      }
      return $hour;
      }

      protected function getMinuteSecond() {
      $minute = [];
      for ($i = 0; $i <= 59; $i++) {
      $minute[] = [
      'label' => $i,
      'value' => $i,
      ];
      }
      return $minute;
      } */
}

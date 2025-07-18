<?php
/**
 * Anowave Magento 2 Google Tag Manager Enhanced Ecommerce (UA) Tracking
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * https://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2024 Anowave (https://www.anowave.com/)
 * @license  	https://www.anowave.com/license-agreement/
 */

namespace Anowave\Ec\vendor\Google;

use Anowave\Ec\vendor\Google\Utils as Google_Utils;
use Anowave\Ec\vendor\Google\Exception as Google_Exception;
use Anowave\Ec\vendor\Google\Model as Google_Model;

class Model implements \ArrayAccess
{
  /**
   * If you need to specify a NULL JSON value, use Google_Model::NULL_VALUE
   * instead - it will be replaced when converting to JSON with a real null. 
   */
  const NULL_VALUE = "{}gapi-php-null";
  
  protected $internal_gapi_mappings = array();
  protected $modelData = array();
  protected $processed = array();

  /**
   * Polymorphic - accepts a variable number of arguments dependent
   * on the type of the model subclass.
   */
  final public function __construct()
  {
    if (func_num_args() == 1 && is_array(func_get_arg(0))) {
      // Initialize the model with the array's contents.
      $array = func_get_arg(0);
      $this->mapTypes($array);
    }
    $this->gapiInit();
  }

  /**
   * Getter that handles passthrough access to the data array, and lazy object creation.
   * @param string $key Property name.
   * @return mixed The value if any, or null.
   */
  public function __get($key)
  {
    $keyTypeName = $this->keyType($key);
    $keyDataType = $this->dataType($key);
    if (isset($this->$keyTypeName) && !isset($this->processed[$key])) {
      if (isset($this->modelData[$key])) {
        $val = $this->modelData[$key];
      } else if (isset($this->$keyDataType) &&
          ($this->$keyDataType == 'array' || $this->$keyDataType == 'map')) {
        $val = array();
      } else {
        $val = null;
      }

      if ($this->isAssociativeArray($val)) {
        if (isset($this->$keyDataType) && 'map' == $this->$keyDataType) {
          foreach ($val as $arrayKey => $arrayItem) {
              $this->modelData[$key][$arrayKey] =
                $this->createObjectFromName($keyTypeName, $arrayItem);
          }
        } else {
          $this->modelData[$key] = $this->createObjectFromName($keyTypeName, $val);
        }
      } else if (is_array($val)) {
        $arrayObject = array();
        foreach ($val as $arrayIndex => $arrayItem) {
          $arrayObject[$arrayIndex] =
            $this->createObjectFromName($keyTypeName, $arrayItem);
        }
        $this->modelData[$key] = $arrayObject;
      }
      $this->processed[$key] = true;
    }

    return isset($this->modelData[$key]) ? $this->modelData[$key] : null;
  }

  /**
   * Initialize this object's properties from an array.
   *
   * @param array $array Used to seed this object's properties.
   * @return void
   */
  protected function mapTypes($array)
  {
    // Hard initialise simple types, lazy load more complex ones.
    foreach ($array as $key => $val) {
      if ( !property_exists($this, $this->keyType($key)) &&
        property_exists($this, $key)) {
          $this->$key = $val;
          unset($array[$key]);
      } elseif (property_exists($this, $camelKey = Google_Utils::camelCase($key))) {
          // This checks if property exists as camelCase, leaving it in array as snake_case
          // in case of backwards compatibility issues.
          $this->$camelKey = $val;
      }
    }
    $this->modelData = $array;
  }

  /**
   * Blank initialiser to be used in subclasses to do  post-construction initialisation - this
   * avoids the need for subclasses to have to implement the variadics handling in their
   * constructors.
   */
  protected function gapiInit()
  {
    return;
  }

  /**
   * Create a simplified object suitable for straightforward
   * conversion to JSON. This is relatively expensive
   * due to the usage of reflection, but shouldn't be called
   * a whole lot, and is the most straightforward way to filter.
   */
  public function toSimpleObject()
  {
    $object = new \stdClass();

    // Process all other data.
    foreach ($this->modelData as $key => $val) {
      $result = $this->getSimpleValue($val);
      if ($result !== null) {
        $object->$key = $this->nullPlaceholderCheck($result);
      }
    }

    // Process all public properties.
    $reflect = new \ReflectionObject($this);
    $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
    foreach ($props as $member) {
      $name = $member->getName();
      $result = $this->getSimpleValue($this->$name);
      if ($result !== null) {
        $name = $this->getMappedName($name);
        $object->$name = $this->nullPlaceholderCheck($result);
      }
    }

    return $object;
  }

  /**
   * Handle different types of values, primarily
   * other objects and map and array data types.
   */
  private function getSimpleValue($value)
  {
    if ($value instanceof Google_Model) {
      return $value->toSimpleObject();
    } else if (is_array($value)) {
      $return = array();
      foreach ($value as $key => $a_value) {
        $a_value = $this->getSimpleValue($a_value);
        if ($a_value !== null) {
          $key = $this->getMappedName($key);
          $return[$key] = $this->nullPlaceholderCheck($a_value);
        }
      }
      return $return;
    }
    return $value;
  }
  
  /**
   * Check whether the value is the null placeholder and return true null.
   */
  private function nullPlaceholderCheck($value)
  {
    if ($value === self::NULL_VALUE) {
      return null;
    }
    return $value;
  }

  /**
   * If there is an internal name mapping, use that.
   */
  private function getMappedName($key)
  {
    if (isset($this->internal_gapi_mappings) &&
        isset($this->internal_gapi_mappings[$key])) {
      $key = $this->internal_gapi_mappings[$key];
    }
    return $key;
  }

  /**
   * Returns true only if the array is associative.
   * @param array $array
   * @return bool True if the array is associative.
   */
  protected function isAssociativeArray($array)
  {
    if (!is_array($array)) {
      return false;
    }
    $keys = array_keys($array);
    foreach ($keys as $key) {
      if (is_string($key)) {
        return true;
      }
    }
    return false;
  }

  /**
   * Given a variable name, discover its type.
   *
   * @param $name
   * @param $item
   * @return object The object from the item.
   */
  private function createObjectFromName($name, $item)
  {
    $type = $this->$name;
    return new $type($item);
  }

  /**
   * Verify if $obj is an array.
   * @throws Google_Exception Thrown if $obj isn't an array.
   * @param array $obj Items that should be validated.
   * @param string $method Method expecting an array as an argument.
   */
  public function assertIsArray($obj, $method)
  {
    if ($obj && !is_array($obj)) {
      throw new Google_Exception(
          "Incorrect parameter type passed to $method(). Expected an array."
      );
    }
  }

  #[\ReturnTypeWillChange] 
  public function offsetExists($offset)
  {
    return isset($this->$offset) || isset($this->modelData[$offset]);
  }

  #[\ReturnTypeWillChange] 
  public function offsetGet($offset)
  {
    return isset($this->$offset) ?
        $this->$offset :
        $this->__get($offset);
  }

  #[\ReturnTypeWillChange] 
  public function offsetSet($offset, $value)
  {
    if (property_exists($this, $offset)) {
      $this->$offset = $value;
    } else {
      $this->modelData[$offset] = $value;
      $this->processed[$offset] = true;
    }
  }

  #[\ReturnTypeWillChange] 
  public function offsetUnset($offset)
  {
    unset($this->modelData[$offset]);
  }

  protected function keyType($key)
  {
    return $key . "Type";
  }

  protected function dataType($key)
  {
    return $key . "DataType";
  }

  public function __isset($key)
  {
    return isset($this->modelData[$key]);
  }

  public function __unset($key)
  {
    unset($this->modelData[$key]);
  }
}

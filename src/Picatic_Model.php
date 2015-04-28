<?php

/**
 * Base Model Operations
 */
class Picatic_Model implements Picatic_Model_Interface, Picatic_Consumer_Interface, ArrayAccess {

  /**
   * Reference to our API
   * @var PicaticAPI
   */
  public $picaticApiInstance = null;

  /**
   * Set the PicaticAPI instance
   * @param PicaticAPI $picaticApi instance of PicaticAPI
   */
  public function setPicaticApi($picaticApi) {
    $this->picaticApiInstance = $picaticApi;
  }

  /**
   * Get the PicaticAPI instance
   * @return PicaticAPI instance of PicaticAPI
   */
  public function getPicaticApi() {
    return $this->picaticApiInstance;
  }

  /**
   * Model values
   * @var array/hash
   */
  public $_values = array();

  /**
   * Optionally construct with an id
   * @param [type] $id [description]
   */
  public function __construct($id=null) {
    $this['id'] = $id;
  }

  /**
   * Implements the Interface for setting a value on our model
   * @param string $k model attribute name
   * @param mixed $v the value
   */
  public function __set($k, $v) {
    $this->_values[$k] = $v;
  }

  /**
   * Test if we have the key
   * @param  string  $k key name
   * @return boolean    true if we do
   */
  public function __isset($k) {
    return isset($this->_values[$k]);
  }

  /**
   * Remove a value by key
   * @param string $k key name
   */
  public function __unset($k) {
    unset($this->_values[$k]);
  }

  /**
   * Get a value by key name
   * @param  string $k key name
   * @return mixed    value of the key
   */
  public function __get($k) {
    if ( array_key_exists($k, $this->_values)) {
      return $this->_values[$k];
    } else {
      //@TODO warning here
      return null;
    }
  }

  public function offsetExists($offset) {
    return isset($this->_values[$offset]);
  }

  public function offsetGet($offset) {
    return isset($this->_values[$offset]) ? $this->_values[$offset] : null;
  }

  public function offsetSet($offset, $value) {
    if (!is_null($offset)) {
      $this->_values[$offset] = $value;
    }
  }

  public function offsetUnset($offset) {
    unset($this->_values[$offset]);
  }

  public function className($className) {
    $parts = explode("_", $className);
    $parts = array_splice($parts, 1);
    return implode("_", $parts);;
  }

  public function classUrl() {
    $class = $this->className(get_class($this));
    return sprintf("%s/%ss", $this->getPicaticApi()->apiVersion, strtolower($class));
  }

  public function instanceUrl() {
    if (isset($this['id'])) {
      $id = $this['id'];
      return sprintf('%s/%s', $this->classUrl(), $id);
    } else {
      return sprintf("%s", $this->classUrl());
    }
  }

  public function refresh() {
    $requestor = $this->getPicaticApi()->requestor();
    $url = $this->instanceUrl();
    $response = $requestor->request('get', $url);
    $this->refreshWithValues($response);
    return $this;
  }

  public function refreshWithValues($a) {
    return $this->_values = $a;
  }

  public function getValues() {
    return $this->_values;
  }

  public function find($id, $params=null) {
    $this['id'] = $id;
    $requestor = $this->getPicaticApi()->requestor();
    $url = $this->instanceUrl();
    $response = $requestor->request('get', $url, null, $params);
    $this->refreshWithValues($response);
    return $this;
  }

  public function findAll($params=array()) {
    $requestor = $this->getPicaticApi()->requestor();
    $response = $requestor->request('get', $this->classUrl(), null, $params);
    $responses = array();
    if ( is_array($response) ) {
      foreach($response as $item) {
        $instance = $this->getPicaticApi()->factory()->modelCreate($this->className(get_class($this)));
        $instance->refreshWithValues($item);
        $responses[] = $instance;
      }
    }
    return $responses;
  }

  public function save() {
    $method = 'post';
    if (isset($this['id'])) {
      $method = 'put';
    }
    $requestor = $this->getPicaticApi()->requestor();
    $response = $requestor->request($method, $this->instanceUrl(), $this->getValues(), null);
    $this->refreshWithValues($response);
    return $this;
  }

  public function instanceAction($action) {
    return $this->instanceActionWithParams($action);
  }

  public function instanceActionWithParams($action, $params=array(), $method=null, $data=null) {
    $requestor = $this->getPicaticApi()->requestor();
    if ($method === null) { $method = 'get'; }
    $url = sprintf("%s/%s", $this->instanceUrl(), $action);
    $response = $requestor->request($method, $url, $data, $params);
    return $response; //@HACK should wrap this in an object model of some sort
  }

  public function classAction($action) {
    return self::staticActionWithParams($action);
  }

  /**
   * Perform a request as a Class action
   * @param  [string] $action [description]
   * @param  [array] $params [description]
   * @param  [string] $method [description]
   * @param  [array] $data   [description]
   * @return [array]         [description]
   */
  public function classActionWithParams($action, $params=null, $method=null, $data=null) {
    $requestor = $this->getPicaticApi()->requestor();
    if ($method === null) { $method = 'get'; }
    $url = sprintf("%s/%s", self::classUrl(), $action);
    $response = $requestor->request($method, $url, $data, $params);
    return $response;
  }
}

<?php

class Picatic_Model implements Picatic_Model_Interface, Picatic_Consumer_Interface, ArrayAccess {

  public $picaticApiInstance = null;

  public function setPicaticApi($picaticApi) {
    $this->picaticApiInstance = $picaticApi;
  }

  public function getPicaticApi() {
    return $this->picaticApiInstance;
  }

  public $_values = array();

  public function __construct($id=null) {
    $this['id'] = $id;
  }

  public function __set($k,$v) {
    $this->_values[$k] = $v;
  }

  public function __isset($k) {
    return isset($this->_values[$k]);
  }

  public function __unset($k) {
    unset($this->_values[$k]);
  }

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
    $parts = explode("_",$className);
    $parts = array_splice($parts,1);
    return implode("_",$parts);;
  }

  public function classUrl() {
    $class = $this->className(get_class($this));
    return sprintf("%s/%ss",$this->getPicaticApi()->apiVersion,strtolower($class));
  }

  public function instanceUrl() {
    if (isset($this['id'])) {
      $id = $this['id'];
      return sprintf('%s/%s',$this->classUrl(),$id);
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

  public function find($id,$params=null) {
    $this['id'] = $id;
    $requestor = $this->getPicaticApi()->requestor();
    $url = $this->instanceUrl();
    $response = $requestor->request('get', $url, null, $params);
    $this->refreshWithValues($response);
    return $this;
  }

  public function findAll($params=array()) {
    $requestor = $this->getPicaticApi()->requestor();
    $response = $requestor->request('get',$this->classUrl(),null,$params);
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
    $response = $requestor->request($method,$this->instanceUrl(),$this->getValues(),null);
    $this->refreshWithValues($response);
    return $this;
  }

  public function instanceAction($action) {
    return $this->actionWithParams($action);
  }

  public function instanceActionWithParams($action,$params=array()) {
    $requestor = $this->getPicaticApi()->requestor();
    $url = sprintf("%s/%s", $this->instanceUrl(), $action);
    $response = $requestor->request('get',$url,null,$params);
    return $response; //@HACK should wrap this in an object model of some sort
  }

  public function classAction($action) {
    return self::staticActionWithParams($action);
  }

  public function classActionWithParams($action,$params=null) {
    $requestor = $this->getPicaticApi()->requestor();
    $url = sprintf("%s/%s", self::classUrl(), $action);
    $response = $requestor->request('get',$url,null,$params);
    return $response;
  }
}
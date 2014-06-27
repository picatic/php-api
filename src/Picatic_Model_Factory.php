<?php

/**
 * Make models on demand
 */
class Picatic_Model_Factory implements Picatic_Model_Factory_Interface, Picatic_Consumer_Interface {

  public $picaticApiInstance = null;

  public function setPicaticApi($picaticApi) {
    $this->picaticApiInstance = $picaticApi;
  }

  public function getPicaticApi() {
    return $this->picaticApiInstance;
  }

  public function modelCreate($class,$values=array()) {
    $fullClassName = sprintf("Picatic_%s", $class);

    $instance = new $fullClassName();
    $instance->setPicaticApi($this->getPicaticApi());
    $instance->refreshWithValues($values);
    return $instance;
  }

  public function modelAction($class,$action) {
    return $this->modelCreate($class)->classAction($action);
  }

  public function modelActionWithParams($class,$action,$params=null) {
    return $this->modelCreate($class)->classActionWithParams($action,$params);
  }
}

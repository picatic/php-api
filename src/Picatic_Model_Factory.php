<?php

/**
 * Make models on demand
 */
class Picatic_Model_Factory implements Picatic_Model_Factory_Interface, Picatic_Consumer_Interface {

  /**
   * API instance that provides routing and authentication
   * @var [type]
   */
  public $picaticApiInstance = null;

  /**
   * Set the PicaticAPI instance
   * @param PicaticAPI $picaticApi
   */
  public function setPicaticApi($picaticApi) {
    $this->picaticApiInstance = $picaticApi;
  }

  /**
   * Get the PicaticAPI instance
   * @return PicaticAPI
   */
  public function getPicaticApi() {
    return $this->picaticApiInstance;
  }

  /**
   * Create a model with optional parameters supplied
   * @param  string $class Name of the model you are loading
   * @param  array  $values initialize the model with the values provided
   * @return object         instance of model
   */
  public function modelCreate($class, $values=array()) {
    $fullClassName = sprintf("Picatic_%s", $class);

    $instance = new $fullClassName();
    $instance->setPicaticApi($this->getPicaticApi());
    $instance->refreshWithValues($values);
    return $instance;
  }

  /**
   * Static action wrapper for models
   * @param  string $class  name of the model you are loading
   * @param  string $action name of model class action
   * @return mixed
   */
  public function modelAction($class, $action) {
    return $this->modelCreate($class)->classAction($action);
  }

  /**
   * Static action wrapper for models
   * @param  string $class  name of the model you are loading
   * @param  string $action name of model class action
   * @param  array $params optional parameters to pass to the class action
   * @return mixed
   */
  public function modelActionWithParams($class, $action, $params=null) {
    return $this->modelCreate($class)->classActionWithParams($action, $params);
  }
}

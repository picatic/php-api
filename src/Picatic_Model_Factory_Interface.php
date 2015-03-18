<?php

/**
 * Interface for generating models
 */
interface Picatic_Model_Factory_Interface {

  /**
   * Should create and optionally initialize a model with the provided values
   * @param  [type] $class  [description]
   * @param  array  $values [description]
   * @return [type]         [description]
   */
  public function modelCreate($class, $values=array());

  /**
   * Execute the models class action and return its result
   * @param  [type] $class  [description]
   * @param  [type] $action [description]
   * @return [type]         [description]
   */
  public function modelAction($class, $action);

  /**
   * Execute the models class action with params and return its result
   * @param  [type] $class  [description]
   * @param  [type] $action [description]
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function modelActionWithParams($class, $action, $params=null);
}

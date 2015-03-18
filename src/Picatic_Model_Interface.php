<?php

interface Picatic_Model_Interface {

  /**
   * Reload the model if it has an id
   * @return instance $this
   */
  public function refresh();

  /**
   * Set the following values on the model, replacing old ones and removing ones that don't exist in the new result
   * @param  array $a [description]
   * @return instance    $this
   */
  public function refreshWithValues($a);

  /**
   * Returns the values of this model
   * @return  assoc associative array of values
   */
  public function getValues();

  /**
   * Get class name with out scope decorators
   * @return string name of class
   */
  public function className($className);

  /**
   * URL Path to models route
   * @return string [description]
   */
  public function classUrl();

  /**
   * URL PAth to the model instance
   * @return [type] [description]
   */
  public function instanceUrl();

  /**
   * fetch a model by id
   * @param  [type] $id [description]
   * @param  [assoc] $params query parameters
   * @return [type]     [description]
   */
  public function find($id, $params=null);

  /**
   * fetch a set of models by id
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function findAll($params=array());

  /**
   * save the model, create it if new
   * @return [type] [description]
   */
  public function save();
  /**
   * preform an action on an instance of a model
   * @param  [type] $name [description]
   * @return [type]       [description]
   */
  public function instanceAction($name);

  /**
   * preform an action on an instance of a model with params
   * @param  [type] $name   [description]
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function instanceActionWithParams($name, $params=array());

  /**
   * Preform an action on a classUrl
   * @param  [type] $name [description]
   * @return [type]       [description]
   */
  public function classAction($name);

  /**
   * Perform an action on classUrl with params
   * @param  [type] $name   [description]
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function classActionWithParams($name, $params=array());


}

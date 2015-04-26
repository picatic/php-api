<?php

// interfaces
require_once('PicaticAPI_Interface.php');
require_once('Picatic_Model_Interface.php');
require_once('Picatic_Requestor_Interface.php');
require_once('Picatic_Model_Factory_Interface.php');
require_once('Picatic_Consumer_Interface.php');

// base cases
require_once('Picatic_Model.php');
require_once('Picatic_Requestor.php');
require_once('Picatic_Model_Factory.php');

// Models
require_once('Picatic_Event.php');
require_once('Picatic_Fee.php');
require_once('Picatic_Key_Value.php');
require_once('Picatic_Option_Flag.php');
require_once('Picatic_Package.php');
require_once('Picatic_Queue.php');
require_once('Picatic_Sponsor.php');
require_once('Picatic_Survey.php');
require_once('Picatic_Survey_Answer.php');
require_once('Picatic_Survey_Question.php');
require_once('Picatic_Survey_Question_Option.php');
require_once('Picatic_Survey_Result.php');
require_once('Picatic_Ticket.php');
require_once('Picatic_Ticket_Price.php');
require_once('Picatic_Ticket_Price_Discount.php');
require_once('Picatic_User.php');
require_once('Picatic_Checkout.php');

// Exceptions
require_once('Picatic_Requestor_Exceptions.php');

/**
 * API Wrapper for Picatic API
 */
class PicaticAPI implements PicaticAPI_Interface {

  public static $instances = array();

  public $apiKey = null;
  public $apiBaseUrl = 'https://api.picatic.com/';
  public $apiVersion = 'v1';
  public $factoryName = 'Picatic_Model_Factory';
  public $requestorName = 'Picatic_Requestor';

  public static function instance($name=null) {
    if ( $name === null ) {
      $name = "_base";
    }
    if ( isset(self::$instances[$name] ) ) {
      return self::$instances[$name];
    } else {
      self::$instances[$name] = new self();
      return self::$instances[$name];
    }
  }

  /**
   * Getter for apiKey
   * @return [string] api key
   */
  public function getApiKey() {
    return $this->apiKey;
  }

  /**
   * Setter for apiKey
   * @param [string] $apiKey api key to use with requests
   */
  public function setApiKey($apiKey) {
    $this->apiKey = $apiKey;
  }

  /**
   * Getter for apiVersion
   * @return [string] get the API version prefix
   */
  public function getApiVersion() {
    return $this->apiVersion;
  }

  /**
   * Setter for apiVersion
   * @param [string] $apiVersion set the API version prefix
   */
  public function setApiVersion($apiVersion) {
    $this->apiVersion = $apiVersion;
  }

  /**
   * Getter for baseUrl to API
   * @return [string] URI to API without version prefix
   */
  public function getApiBaseUrl() {
    return $this->apiBaseUrl;
  }

  /**
   * Setter for baseUrl to API
   * @param [string] $apiBaseUrl URI to API without verion prefix
   */
  public function setApiBaseUrl($apiBaseUrl) {
    $baseUrl = parse_url($apiBaseUrl);
    $this->apiBaseUrl = http_build_url($baseUrl);
  }

  /**
   * Get the model factory
   * @return [Picatic_Model_Factory_Interface] instance of a model factory
   */
  public function factory() {
    $factory = new $this->factoryName();
    $factory->setPicaticApi($this);
    return $factory;
  }

  /**
   * Get Request handler
   * @return [Picatic_Requestor_Interface] new instance of a Requestor
   */
  public function requestor() {
    $requestor = new $this->requestorName();
    $requestor->setPicaticApi($this);
    return $requestor;
  }

}

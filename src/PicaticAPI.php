<?php

// Composer Autoload
require_once('../vendor/autoload.php');

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
require_once('Picatic_Fee.php');
require_once('Picatic_Event.php');
require_once('Picatic_Ticket_Price.php');
require_once('Picatic_User.php');

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

  public function getApiKey() {
    return $this->apiKey;
  }

  public function setApiKey($apiKey) {
    $this->apiKey = $apiKey;
  }

  public function getApiVersion() {
    return $this->apiVersion;
  }

  public function setApiVersion($apiVersion) {
    return $this->apiVersion;
  }

  public function getApiBaseUrl() {
    return $this->apiBaseUrl;
  }

  public function setApiBaseUrl($apiBaseUrl) {
    $this->apiBaseUrl = $apiBaseUrl;
  }

  public function factory() {
    $factory = new $this->factoryName();
    $factory->setPicaticApi($this);
    return $factory;
  }

  public function requestor() {
    $requestor = new $this->requestorName();
    $requestor->setPicaticApi($this);
    return $requestor;
  }

}
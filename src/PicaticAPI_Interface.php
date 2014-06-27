<?php

/**
 * @todo abstract the PicaticAPI to use this and allow mocking of the core
 */
interface PicaticAPI_Interface {

  /**
   *
   * @param  string $name   named instance, if you want to have more than one with different configurations
   * @param  array  $config configure the instance, mapped to instance values
   * @return PicaticAPI
   */
  public static function instance($name=null);

  public function getApiKey();

  public function setApiKey($apiKey);

  public function getApiVersion();

  public function setApiVersion($apiVersion);

  public function getApiBaseUrl();

  public function setApiBaseUrl($apiBaseUrl);

  public function factory();

  public function requestor();
}

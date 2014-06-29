<?php

/**
 * Provided standardized way to request resources
 */
interface Picatic_Requestor_Interface {

  /**
   * Handle request to API
   * @param  string $method [description]
   * @param  string $url    [description]
   * @param  array/string $data  [description]
   * @param  array $params [description]
   * @return array         [description]
   */
  public function request($method, $url, $data=null, $params=null);

  /**
   * Take a relative API Url path and make it whole
   * @param  [type] $path [description]
   * @return [type]       [description]
   */
  public function apiUrl($path);
}

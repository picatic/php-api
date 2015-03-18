<?php

class Picatic_Key_Value extends Picatic_Model {

  public function get($name, $id) {
    $url = sprintf("%s/%s/%s", $this->instanceUrl(), $name, $id);
    $requestor = $this->getPicaticApi()->requestor();
    $response = $requestor->request('get', $url, null, null);
    return $response;
  }
}

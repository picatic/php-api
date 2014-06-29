<?php

class Picatic_Fee extends Picatic_Model {

  public function rate($params=array()) {
    return $this->classActionWithParams('rate', $params);
  }

  public function best($params=array()) {
    return $this->classActionWithParams('best', $params);
  }

}

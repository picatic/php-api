<?php

class Picatic_Queue extends Picatic_Model {

  public function createJob($job=array()) {
    return $this->classActionWithParams('jobs', null, 'post', $job);
  }

}

<?php

class Picatic_Queue extends Picatic_Model {

  public function createJob($job) {
    return $this->classActionWithParams('jobs', null, 'post', $job);
  }

}

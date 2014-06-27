<?php

interface Picatic_Model_Factory_Interface {

  public function modelCreate($class,$values=array());

  public function modelAction($class,$action);

  public function modelActionWithParams($class,$action,$params=null);
}

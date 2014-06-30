<?php

// Include PicaticAPI, which includes everything else you need
include('../vendor/autoload.php');
include('../src/PicaticAPI.php');
include('config.php');

// Get you
$user = PicaticAPI::instance()->factory()->modelCreate('User')->find('me');

// Show your user profile values
print_r($user->getValues());

// Fetch the default instance, get the model factory, get the Event model and find the event 34641
$events = PicaticAPI::instance()->factory()->modelCreate('Event')->findAll(array(
  'user_id'=>$user['id'],
  'fields' => 'id,title'
  )
);

// print out the response
foreach($events as $event) {
  print_r($event->getValues());
}

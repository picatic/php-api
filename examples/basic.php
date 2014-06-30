<?php

// Include PicaticAPI, which includes everything else you need
include('../vendor/autoload.php');
include('../src/PicaticAPI.php');
include('config.php');

// Fetch the default instance, get the model factory, get the Event model and find the event 34641
$event = PicaticAPI::instance()->factory()->modelCreate('Event')->find(34641);

// print out the response
print_r($event->getValues());

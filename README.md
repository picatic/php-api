Picatic API PHP
===============

What you need to know

* Everything has an interface, everything is pluggable
* We avoid statics for almost everything to make testing easier
* There are some odd wrapping functions/classes as a result of our adversion to static methods
* We break the ideal that static and instance methods operate on those levels.

Our "static" methods are prefixed with "class": classRateLookup

In some special cases, we prefix instance methods with "instance". Usually only when there is a similarly named "class" method.

Composer
========

You can include the latest version of this package by adding `"picatic/php-api": "dev-master"` to your "require".

How to Use
==========

Get an instance of the API:

```
$picaticInstance = PicaticAPI::instance();
```

Configure API with your key:

```
$picaticInstance->setApiKey('sk_live_123');
```

Find an Event:

```
$event = PicaticAPI::instance()->factory()->modelCreate('Event')->find(34641);
```

This returns an instance of Picatic_Event model. If you want to work with it as an associative array, you can call `getValues` to return an associative array.

```
$eventAssoc = $event->getValues();
```

Find a bunch of events:

```
$events = PicaticAPI::instance()->factory()->modelCreate('Event')->findAll(array('user_id'=>6679));
```

Update an event:

```
$event['description'] = 'This is going to rock';
$event->save();
```


# Event dispatcher for component based objects

A simple event dispatcher which works with `"dakwamine/component-based-object": "^1.0"` and `"psr/event-dispatcher": "^1.0"`.

## Basic usage

```php
<?php

class SomeEvent implements \Dakwamine\Component\Event\EventInterface
{
  const EVENT_NAME = 'Any string';

  public function getName(){
    return static::EVENT_NAME;
  }
}

class SomeEventListener implements \Dakwamine\Component\Event\EventListenerInterface
{
  public function handleEvent(\Dakwamine\Event\EventInterface $event) {
    // Do stuff related to $event, like dispatching to other methods.
    if ($event->getName() === SomeEvent::EVENT_NAME) {
      // ...
    }
  }
}

// This object holds the lists of class names per event.
$listenerProvider = new \Dakwamine\Component\Event\ListenerProvider();

// Register the listener. Optionally set the priority.
$listenerProvider->addListener(SomeEvent::EVENT_NAME, SomeEventListener::class, 42);

// Dispatch the event. Listeners will be instantiated or retrieved by the listener provider.
$eventDispatcher = new \Dakwamine\Component\Event\EventDispatcher($listenerProvider);
$eventDispatcher->dispatch(new SomeEvent());
```

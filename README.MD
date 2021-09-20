# Event dispatcher for component based objects

A simple event dispatcher which works with `"dakwamine/component-based-object": "^1.0"` and `"psr/event-dispatcher": "^1.0"`.

## Basic usage

```php
<?php

use Dakwamine\Component\Event\EventDispatcher;
use Dakwamine\Component\Event\EventInterface;
use Dakwamine\Component\Event\EventListenerInterface;
use Dakwamine\Component\Event\ListenerProvider;

class SomeEvent implements EventInterface
{
  public const EVENT_NAME = 'Any string';

  public function getName(): string
  {
    return static::EVENT_NAME;
  }
}

class SomeEventListener implements EventListenerInterface
{
  public function handleEvent(EventInterface $event): void
  {
    // Do stuff related to $event, like dispatching to other methods.
    if ($event->getName() === SomeEvent::EVENT_NAME) {
      // ...
    }
  }
}

// This object holds the lists of class names per event.
$listenerProvider = new ListenerProvider();

// Register the listener. Optionally set the priority.
$listenerProvider->addListener(SomeEvent::EVENT_NAME, SomeEventListener::class, 42);

// Dispatch the event. Listeners will be instantiated or retrieved by the listener provider.
$eventDispatcher = new EventDispatcher($listenerProvider);
$eventDispatcher->dispatch(new SomeEvent());
```

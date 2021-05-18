<?php

namespace Dakwamine\Component\Event;

use Dakwamine\Component\ComponentBasedObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * The event dispatcher.
 */
class EventDispatcher extends ComponentBasedObject implements EventDispatcherInterface
{
    /**
     * The listener provider holding the list of listeners.
     *
     * @var ListenerProviderInterface
     */
    private $listenerProvider;

    /**
     * EventDispatcher constructor.
     *
     * @param ListenerProvider|null $listenerProvider
     *   Listener provider. Leave empty for global listener provider.
     */
    public function __construct(ListenerProvider $listenerProvider = null)
    {
        if (!empty($listenerProvider)) {
            $this->listenerProvider = $listenerProvider;
        } else {
            $this->listenerProvider =
                static::getSharedComponentByClassName(ListenerProvider::class, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event)
    {
        if (!$event instanceof EventInterface) {
            // Not an event one can handle.
            return $event;
        }

        $isStoppable = $event instanceof StoppableEventInterface;

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            /** @var EventListenerInterface $listener */
            $listener->handleEvent($event);

            if (!$isStoppable) {
                continue;
            }

            /** @var StoppableEventInterface $event */
            if ($event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }
}

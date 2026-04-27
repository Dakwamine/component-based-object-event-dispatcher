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
                ComponentBasedObject::getRootComponentByClassName(ListenerProvider::class, true);
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

    /**
     * Dispatches an event while isolating listener failures.
     *
     * @param object $event
     *   Event to dispatch.
     * @param callable|null $onListenerError
     *   Optional callback called as:
     *   fn(\Throwable $throwable, EventInterface $event, EventListenerInterface $listener): void
     */
    public function dispatchWithExceptionHandling(object $event, ?callable $onListenerError = null)
    {
        if (!$event instanceof EventInterface) {
            return $event;
        }

        $isStoppable = $event instanceof StoppableEventInterface;

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            /** @var EventListenerInterface $listener */
            try {
                $listener->handleEvent($event);
            } catch (\Throwable $throwable) {
                if (!empty($onListenerError)) {
                    $onListenerError($throwable, $event, $listener);
                }
            }

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

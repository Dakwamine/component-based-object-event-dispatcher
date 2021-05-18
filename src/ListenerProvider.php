<?php

namespace Dakwamine\Component\Event;

use Dakwamine\Component\ComponentBasedObject;

/**
 * Listener provider.
 */
class ListenerProvider extends ComponentBasedObject implements ListenerProviderInterface
{
    /**
     * Listener groups. They contain listeners grouped by the same event.
     *
     * @var ListenerGroupInterface[]
     */
    private $listenerGroups = [];

    /**
     * Adds a listener.
     *
     * @param string $eventName
     *   Event name.
     * @param string $listenerClassName
     *   The listener class name, which will be used for fetch.
     * @param int $priority
     *   The priority of this listener for the given event name.
     */
    public function addListener($eventName, $listenerClassName, $priority = 0)
    {
        if (empty($eventName) || !is_string($eventName)) {
            // Do not work with it.
            return;
        }

        if (empty($this->listenerGroups[$eventName])) {
            // Create the listener group.
            $this->listenerGroups[$eventName] = new ListenerGroup();
        }

        $this->listenerGroups[$eventName]->addListener($listenerClassName, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function getListenersForEvent(object $event): iterable
    {
        if (!$event instanceof EventInterface) {
            // Not an event one can handle.
            return [];
        }

        if (empty($this->listenerGroups[$event->getName()])) {
            // No listener for this event.
            return [];
        }

        foreach ($this->listenerGroups[$event->getName()]->getListeners() as $listenerClassName) {
            // Retrieve the listener instance.
            $instance = $this->getComponentByClassName($listenerClassName, true);

            if (empty($instance)) {
                // Failed to load the listener.
                continue;
            }

            yield $instance;
        }
    }
}

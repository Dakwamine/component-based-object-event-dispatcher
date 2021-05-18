<?php

namespace Dakwamine\Component\Event;

/**
 * Interface for event listeners.
 */
interface EventListenerInterface
{
    /**
     * Handles the event(s).
     *
     * @param EventInterface $event
     *   The object containing event details.
     *   Must implement EventInterface.
     *   May implement StoppableEventInterface.
     */
    public function handleEvent(EventInterface $event);
}

<?php

namespace Dakwamine\Component\Event;

/**
 * Interface for listener groups.
 */
interface ListenerGroupInterface
{
    /**
     * Adds the listener.
     *
     * @param string $eventListenerClassName
     *   The event listener class name to get/instantiate from the component registry.
     * @param int $priority
     *   Priority for listener sorting.
     */
    public function addListener($eventListenerClassName, $priority = 0);

    /**
     * Gets the listeners list.
     *
     * @return string[]
     *   Array of listeners class names, ordered by priority.
     */
    public function getListeners();
}

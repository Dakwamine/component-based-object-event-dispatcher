<?php

namespace Dakwamine\Component\Event;

use Psr\EventDispatcher\ListenerProviderInterface as PsrListenerProviderInterface;

/**
 * Extends the PSR-14 listener provider for extra methods.
 */
interface ListenerProviderInterface extends PsrListenerProviderInterface
{
    /**
     * Adds a listener to the list of listeners.
     *
     * @param string $eventName
     *   The event name.
     * @param string $listenerClassName
     *   The listener class name to use when fetching from the component registry.
     * @param int $priority
     *   The priority. Lower values are called first.
     */
    public function addListener(string $eventName, string $listenerClassName, int $priority = 0): void;
}

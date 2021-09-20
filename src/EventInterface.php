<?php

namespace Dakwamine\Component\Event;

/**
 * Interface for events.
 */
interface EventInterface
{
    /**
     * Returns the event name.
     *
     * @return string
     *   The event name.
     */
    public function getName(): string;
}
